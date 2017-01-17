<table
    class="swimlanes-table table-striped table-scrolling"
    data-save-position-url="<?= $this->url->href('SwimlaneController', 'move', array('project_id' => $project['id'])) ?>">
    <thead>
        <tr>
            <th><?= t('Name') ?></th>
            <th class="column-8"><?= t('Actions') ?></th>
        </tr>

        <?php if (! empty($default_swimlane)): ?>
        <tr>
            <td>
                <?= $this->text->e($default_swimlane['default_swimlane']) ?>
                <?php if ($default_swimlane['default_swimlane'] !== t('Default swimlane')): ?>
                    &nbsp;(<?= t('Default swimlane') ?>)
                <?php endif ?>
            </td>
            <td>
                <div class="dropdown">
                <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <?= $this->modal->medium('edit', t('Edit'), 'SwimlaneController', 'editDefault', array('project_id' => $project['id'])) ?>
                    </li>
                    <li>
                        <?php if ($default_swimlane['show_default_swimlane'] == 1): ?>
                            <?= $this->url->icon('toggle-off', t('Disable'), 'SwimlaneController', 'disableDefault', array('project_id' => $project['id']), true) ?>
                        <?php else: ?>
                            <?= $this->url->icon('toggle-on', t('Enable'), 'SwimlaneController', 'enableDefault', array('project_id' => $project['id']), true) ?>
                        <?php endif ?>
                    </li>
                </ul>
            </td>
        </tr>
        <?php endif ?>
    </thead>
    <tbody>
        <?php foreach ($swimlanes as $swimlane): ?>
        <tr data-swimlane-id="<?= $swimlane['id'] ?>">
            <td>
                <?php if (! isset($disable_handler)): ?>
                    <i class="fa fa-arrows-alt draggable-row-handle" title="<?= t('Change column position') ?>"></i>
                <?php endif ?>

                <?= $this->text->e($swimlane['name']) ?>

                <?php if (! empty($swimlane['description'])): ?>
                    <span class="tooltip" title="<?= $this->text->markdownAttribute($swimlane['description']) ?>">
                        <i class="fa fa-info-circle"></i>
                    </span>
                <?php endif ?>
            </td>
            <td>
                <div class="dropdown">
                <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <?= $this->modal->medium('edit', t('Edit'), 'SwimlaneController', 'edit', array('project_id' => $project['id'], 'swimlane_id' => $swimlane['id'])) ?>
                    </li>
                    <li>
                        <?php if ($swimlane['is_active']): ?>
                            <?= $this->url->icon('toggle-off', t('Disable'), 'SwimlaneController', 'disable', array('project_id' => $project['id'], 'swimlane_id' => $swimlane['id']), true) ?>
                        <?php else: ?>
                            <?= $this->url->icon('toggle-on', t('Enable'), 'SwimlaneController', 'enable', array('project_id' => $project['id'], 'swimlane_id' => $swimlane['id']), true) ?>
                        <?php endif ?>
                    </li>
                    <li>
                        <?= $this->modal->confirm('trash-o', t('Remove'), 'SwimlaneController', 'confirm', array('project_id' => $project['id'], 'swimlane_id' => $swimlane['id'])) ?>
                    </li>
                </ul>
                </div>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
