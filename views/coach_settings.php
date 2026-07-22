<?php
/**
 * Persona settings — persona selection + per-activity field overrides (NMStream personas).
 *
 * Expected variables: $messagestream, $cm, $course, $context, $cmid (set by view.php).
 *
 * @package    mod_messagestream
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $DB;

$currentpersonaid = (int) ($messagestream->persona_id ?? 0);
$currentoverrides = !empty($messagestream->persona_overrides_json)
    ? (json_decode($messagestream->persona_overrides_json, true) ?? [])
    : [];

$personas = [];
if ($DB->get_manager()->table_exists('local_nmstream_personas')) {
    $personas = $DB->get_records('local_nmstream_personas', null, 'name ASC', 'id, name');
}

$baseconfig = [];
$personaservice = new \local_nmstream\local\persona\PersonaService();
if ($currentpersonaid) {
    $personarec = $DB->get_record('local_nmstream_personas', ['id' => $currentpersonaid]);
    if ($personarec) {
        $baseconfig = json_decode($personarec->config_json, true) ?? [];
        if (!isset($baseconfig['name']) && !empty($personarec->name)) {
            $baseconfig['name'] = $personarec->name;
        }
        $baseconfig['task'] = \local_nmstream\local\persona\PersonaCompiler::resolve_task($baseconfig['task'] ?? '');
        if (!empty($personarec->solution_hints_encrypted)) {
            $baseconfig['solution_hints'] = $personaservice->decrypt_hints($personarec->solution_hints_encrypted);
        }
    }
}
// Effective contract defaults (persona → site → bundled) for grayed-out preview when not overridden.
$contractdefaults = \local_nmstream\local\persona\PersonaCompiler::resolve_contract_parts($baseconfig);
$baseconfig['output_schema'] = $contractdefaults['output_schema'];
$baseconfig['citation_rules'] = $contractdefaults['citation_rules'];
$baseconfig['validation_checklist'] = $contractdefaults['validation_checklist'];

/**
 * One override row (checkbox + input).
 *
 * @param string $key Field key (see PersonaService::OVERRIDE_FIELDS)
 * @param string $label Display label
 * @param string $type text|textarea|select|file
 * @param array $options For select: value => label
 * @param array $overrides Current activity overrides
 * @param array $base Persona/site default values (shown grayed when not overridden)
 * @param int $textarearows Rows for textarea fields
 */
function mod_messagestream_coach_override_row(
    string $key,
    string $label,
    string $type,
    array $options,
    array $overrides,
    array $base,
    int $textarearows = 3
): void {
    $checked = array_key_exists($key, $overrides);
    $basevalue = $base[$key] ?? '';
    if (is_array($basevalue)) {
        $basevalue = implode("\n", $basevalue);
    }
    $overridevalue = $overrides[$key] ?? '';
    if (is_array($overridevalue)) {
        $overridevalue = implode("\n", $overridevalue);
    }
    $value = $checked ? $overridevalue : $basevalue;
    $placeholder = $basevalue;
    $disabled = $checked ? '' : 'disabled';
    $chk = $checked ? 'checked' : '';
    $defattr = htmlspecialchars((string) $basevalue, ENT_QUOTES);
    ?>
    <tr class="override-row" data-key="<?php echo htmlspecialchars($key); ?>" data-default="<?php echo $defattr; ?>">
        <td class="override-toggle">
            <input type="checkbox" class="override-check" data-key="<?php echo htmlspecialchars($key); ?>"
                   <?php echo $chk; ?> title="<?php echo get_string('coachsettings:override_activate', 'mod_messagestream'); ?>">
        </td>
        <td class="override-label"><?php echo htmlspecialchars($label); ?></td>
        <td class="override-input">
            <?php if ($type === 'select') : ?>
            <select name="override_<?php echo htmlspecialchars($key); ?>" class="override-field form-control form-control-sm" <?php echo $disabled; ?>>
                <?php foreach ($options as $v => $l) : ?>
                <option value="<?php echo htmlspecialchars((string) $v); ?>" <?php echo ((string) $value === (string) $v) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars((string) $l); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <?php elseif ($type === 'textarea') : ?>
            <textarea name="override_<?php echo htmlspecialchars($key); ?>" class="override-field form-control form-control-sm"
                      rows="<?php echo (int) $textarearows; ?>" placeholder="<?php echo htmlspecialchars((string) $placeholder); ?>"
                      <?php echo $disabled; ?>><?php echo htmlspecialchars((string) $value); ?></textarea>
            <?php elseif ($type === 'file') : ?>
            <div class="d-flex gap-2 flex-wrap">
                <input type="text" name="override_<?php echo htmlspecialchars($key); ?>" class="override-field form-control form-control-sm"
                       value="<?php echo htmlspecialchars((string) $value); ?>"
                       placeholder="<?php echo get_string('coachsettings:avatar_placeholder', 'mod_messagestream'); ?>"
                       <?php echo $disabled; ?> style="flex:1;min-width:140px;">
                <input type="file" class="override-file-input form-control form-control-sm" data-key="<?php echo htmlspecialchars($key); ?>"
                       accept=".png,.jpg,.jpeg,.svg,.gif" <?php echo $disabled; ?> style="flex:1;min-width:140px;">
            </div>
            <small class="text-muted d-block mt-1"><?php echo get_string('coachsettings:avatar_hint', 'mod_messagestream'); ?></small>
            <?php else : ?>
            <input type="text" name="override_<?php echo htmlspecialchars($key); ?>" class="override-field form-control form-control-sm"
                   value="<?php echo htmlspecialchars((string) $value); ?>"
                   placeholder="<?php echo htmlspecialchars((string) $placeholder); ?>"
                   <?php echo $disabled; ?>>
            <?php endif; ?>
        </td>
        <td class="override-hint text-muted small">
            <?php if ($basevalue !== '') : ?>
                <em><?php echo htmlspecialchars(mb_substr((string) $basevalue, 0, 80)); ?></em>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}

$tonalities = [
    'objective' => get_string('personas:tonality_objective', 'local_nmstream'),
    'casual' => get_string('personas:tonality_casual', 'local_nmstream'),
    'enthusiastic' => get_string('personas:tonality_enthusiastic', 'local_nmstream'),
    'critical-constructive' => get_string('personas:tonality_critical', 'local_nmstream'),
];
$salutations = [
    'Du' => get_string('personas:salutation_value_du', 'local_nmstream'),
    'Sie' => get_string('personas:salutation_value_sie', 'local_nmstream'),
    'Collegial Sie' => get_string('personas:salutation_value_collegial_sie', 'local_nmstream'),
];
$perspectives = [
    'I' => get_string('personas:perspective_i', 'local_nmstream'),
    'We' => get_string('personas:perspective_we', 'local_nmstream'),
];
$structures = [
    'A' => get_string('personas:structure_a', 'local_nmstream'),
    'B' => get_string('personas:structure_b', 'local_nmstream'),
];
$sourcemodes = [
    'strict' => get_string('personas:source_strict', 'local_nmstream'),
    'open' => get_string('personas:source_open', 'local_nmstream'),
];
$discussionmodes = [
    'never' => get_string('personas:discussion_never', 'local_nmstream'),
    'always' => get_string('personas:discussion_always', 'local_nmstream'),
    'random' => get_string('personas:discussion_random', 'local_nmstream'),
];
$interactionstyles = [
    'balanced' => get_string('personas:interaction_balanced', 'local_nmstream'),
    'socratic' => get_string('personas:interaction_socratic', 'local_nmstream'),
    'direct' => get_string('personas:interaction_direct', 'local_nmstream'),
    'devils_advocate' => get_string('personas:interaction_devils_advocate', 'local_nmstream'),
    'coach_ideator' => get_string('personas:interaction_coach_ideator', 'local_nmstream'),
];
$closingtypes = [
    'motivating' => get_string('personas:closing_motivating', 'local_nmstream'),
    'humorous' => get_string('personas:closing_humorous', 'local_nmstream'),
    'feedback_thumbs' => get_string('personas:closing_thumbs', 'local_nmstream'),
    'cta' => get_string('personas:closing_cta', 'local_nmstream'),
    'summary_structured' => get_string('personas:closing_summary_structured', 'local_nmstream'),
    'custom' => get_string('personas:field_closing_custom', 'local_nmstream'),
];

$ajaxurl = (new moodle_url('/mod/messagestream/ajax.php'))->out(false);
?>

<div class="messagestream-coach-settings mt-3">
    <h3><?php echo get_string('personas:activity_settings_tab', 'local_nmstream'); ?></h3>

    <div id="coach-settings-status" class="mb-3" role="alert" aria-live="polite" style="display:none;"></div>
    <pre id="coach-settings-preview" class="small text-muted" style="display:none;white-space:pre-wrap;max-height:12rem;overflow:auto;border:1px solid #e5e7eb;border-radius:8px;padding:10px;background:#fafafa;"></pre>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo get_string('coachsettings:persona', 'mod_messagestream'); ?></h5>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <select id="coach-persona-id" class="form-control" style="max-width:320px;">
                    <option value="0"><?php echo get_string('persona_none', 'mod_messagestream'); ?></option>
                    <?php foreach ($personas as $p) : ?>
                    <option value="<?php echo (int) $p->id; ?>" <?php echo ($currentpersonaid === (int) $p->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars(format_string($p->name)); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($currentpersonaid) : ?>
                <a href="<?php echo (new moodle_url('/local/nmstream/edit_persona.php', ['id' => $currentpersonaid]))->out(false); ?>"
                   class="btn btn-sm btn-outline-secondary">
                    <?php echo get_string('coachsettings:edit_persona', 'mod_messagestream'); ?>
                </a>
                <?php endif; ?>
                <a href="<?php echo (new moodle_url('/local/nmstream/edit_persona.php'))->out(false); ?>"
                   class="btn btn-sm btn-outline-primary">
                    <?php echo get_string('coachsettings:new_persona', 'mod_messagestream'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo get_string('coachsettings:overrides_heading', 'mod_messagestream'); ?></h5>
            <p class="text-muted small mb-3"><?php echo get_string('coachsettings:override_hint', 'mod_messagestream'); ?></p>

            <table class="table table-sm coach-override-table">
                <thead class="thead-light">
                    <tr>
                        <th style="width:32px;"></th>
                        <th><?php echo get_string('coachsettings:field', 'mod_messagestream'); ?></th>
                        <th><?php echo get_string('coachsettings:value', 'mod_messagestream'); ?></th>
                        <th><?php echo get_string('coachsettings:persona_default', 'mod_messagestream'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <tr class="table-secondary"><td colspan="4"><strong><?php echo get_string('personas:section_style', 'local_nmstream'); ?></strong></td></tr>
                <?php
                mod_messagestream_coach_override_row('name', get_string('personas:field_name', 'local_nmstream'), 'text', [], $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('salutation', get_string('personas:field_salutation', 'local_nmstream'), 'select', $salutations, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('role', get_string('personas:field_role', 'local_nmstream'), 'text', [], $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('tonality', get_string('personas:field_tonality', 'local_nmstream'), 'select', $tonalities, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('perspective', get_string('personas:field_perspective', 'local_nmstream'), 'select', $perspectives, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('prohibited_phrases', get_string('personas:field_prohibited_phrases', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('style_rules', get_string('personas:field_style_rules', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('avatar', get_string('personas:field_avatar', 'local_nmstream'), 'file', [], $currentoverrides, $baseconfig);
                ?>
                <tr class="table-secondary"><td colspan="4"><strong><?php echo get_string('personas:section_feedback', 'local_nmstream'); ?></strong></td></tr>
                <?php
                mod_messagestream_coach_override_row('prefix', get_string('personas:field_prefix', 'local_nmstream'), 'text', [], $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('structure', get_string('personas:field_structure', 'local_nmstream'), 'select', $structures, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('max_length', get_string('personas:field_max_length', 'local_nmstream'), 'text', [], $currentoverrides, $baseconfig);
                ?>
                <tr class="table-secondary"><td colspan="4"><strong><?php echo get_string('personas:section_knowledge', 'local_nmstream'); ?></strong></td></tr>
                <?php
                mod_messagestream_coach_override_row('source_mode', get_string('personas:field_source_mode', 'local_nmstream'), 'select', $sourcemodes, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('audience', get_string('personas:field_audience', 'local_nmstream'), 'text', [], $currentoverrides, $baseconfig);
                ?>
                <tr class="table-secondary"><td colspan="4"><strong><?php echo get_string('personas:section_task', 'local_nmstream'); ?></strong></td></tr>
                <?php
                mod_messagestream_coach_override_row('task', get_string('personas:field_task', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig, 5);
                mod_messagestream_coach_override_row('solution_hints', get_string('personas:field_solution_hints', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig, 5);
                ?>
                <tr class="table-secondary"><td colspan="4"><strong><?php echo get_string('personas:section_interaction', 'local_nmstream'); ?></strong></td></tr>
                <?php
                mod_messagestream_coach_override_row('interaction_style', get_string('personas:field_interaction_style', 'local_nmstream'), 'select', $interactionstyles, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('discussion_mode', get_string('personas:field_discussion_mode', 'local_nmstream'), 'select', $discussionmodes, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('closing_type', get_string('personas:field_closing_type', 'local_nmstream'), 'select', $closingtypes, $currentoverrides, $baseconfig);
                mod_messagestream_coach_override_row('closing_custom_text', get_string('personas:field_closing_custom', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig);
                ?>
                <tr class="table-secondary"><td colspan="4"><strong><?php echo get_string('personas:section_output_contract', 'local_nmstream'); ?></strong></td></tr>
                <tr><td colspan="4" class="small text-muted pb-2"><?php echo get_string('personas:section_output_contract_desc', 'local_nmstream'); ?></td></tr>
                <?php
                mod_messagestream_coach_override_row('output_schema', get_string('personas:field_output_schema', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig, 10);
                mod_messagestream_coach_override_row('citation_rules', get_string('personas:field_citation_rules', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig, 8);
                mod_messagestream_coach_override_row('validation_checklist', get_string('personas:field_validation_checklist', 'local_nmstream'), 'textarea', [], $currentoverrides, $baseconfig, 8);
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <button id="coach-settings-save" type="button" class="btn btn-primary">
        <?php echo get_string('coachsettings:save', 'mod_messagestream'); ?>
    </button>
</div>

<style>
.coach-override-table td { vertical-align: middle; }
.override-input .override-field:disabled { background: #f8f9fa; color: #6c757d; }
.override-input textarea.override-field:disabled { max-height: 14rem; overflow: auto; white-space: pre-wrap; }
.override-hint { max-width: 220px; }
</style>

<script>
(function() {
    document.querySelectorAll('.override-check').forEach(function(chk) {
        chk.addEventListener('change', function() {
            var row = this.closest('.override-row');
            var field = row.querySelector('.override-field');
            var fileInput = row.querySelector('.override-file-input');
            var def = row.getAttribute('data-default');
            if (def === null) {
                def = '';
            }
            field.disabled = !this.checked;
            if (fileInput) {
                fileInput.disabled = !this.checked;
            }
            if (this.checked) {
                // Start editable value from the current default preview if empty.
                if (!field.value && def) {
                    field.value = def;
                }
                field.focus();
            } else {
                // Restore grayed-out default preview when override is turned off.
                field.value = def;
                if (fileInput) {
                    fileInput.value = '';
                }
            }
        });
    });

    document.querySelectorAll('.override-file-input').forEach(function(fileInput) {
        fileInput.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) {
                return;
            }
            var reader = new FileReader();
            reader.onload = function(e) {
                var row = fileInput.closest('.override-row');
                var textField = row.querySelector('.override-field');
                textField.value = e.target.result;
                textField.title = file.name;
            };
            reader.readAsDataURL(file);
        });
    });

    var saveBtn = document.getElementById('coach-settings-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            var overrides = {};
            document.querySelectorAll('.override-check:checked').forEach(function(chk) {
                var key = chk.dataset.key;
                var row = chk.closest('.override-row');
                var field = row.querySelector('.override-field');
                overrides[key] = field.value;
            });

            var btn = this;
            btn.disabled = true;

            var params = new URLSearchParams({
                action: 'save_coach_settings',
                cmid: <?php echo (int) $cmid; ?>,
                sesskey: M.cfg.sesskey,
                persona_id: document.getElementById('coach-persona-id').value,
                overrides: JSON.stringify(overrides),
            });

            fetch(<?php echo json_encode($ajaxurl); ?>, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: params.toString(),
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var status = document.getElementById('coach-settings-status');
                var preview = document.getElementById('coach-settings-preview');
                if (!status) return;
                if (data && data.success) {
                    status.className = 'alert alert-success';
                    status.textContent = <?php echo json_encode(get_string('coachsettings:saved', 'mod_messagestream')); ?>;
                    status.style.display = '';
                    try { status.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (e) {}
                    if (preview) {
                        var p = (data.compiled_preview || '').trim();
                        if (p) {
                            preview.textContent = p;
                            preview.style.display = '';
                        } else {
                            preview.style.display = 'none';
                        }
                    }
                } else {
                    status.className = 'alert alert-danger';
                    status.textContent = (data && data.error) ? data.error : <?php echo json_encode(get_string('coachsettings:error_save', 'mod_messagestream')); ?>;
                    status.style.display = '';
                    if (preview) preview.style.display = 'none';
                }
            })
            .catch(function() {
                var status = document.getElementById('coach-settings-status');
                status.className = 'alert alert-danger';
                status.textContent = <?php echo json_encode(get_string('coachsettings:error_request', 'mod_messagestream')); ?>;
                status.style.display = '';
            })
            .finally(function() { btn.disabled = false; });
        });
    }
})();
</script>
