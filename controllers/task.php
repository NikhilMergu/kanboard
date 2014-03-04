<?php

namespace Controller;

class Task extends Base
{
    // Webhook to create a task (useful for external software)
    public function add()
    {
        $token = $this->request->getStringParam('token');

        if ($this->config->get('webhooks_token') !== $token) {
            $this->response->text('Not Authorized', 401);
        }

        $projectModel = new \Model\Project;
        $defaultProject = $projectModel->getFirst();

        $values = array(
            'title' => $this->request->getStringParam('title'),
            'description' => $this->request->getStringParam('description'),
            'color_id' => $this->request->getStringParam('color_id', 'blue'),
            'project_id' => $this->request->getIntegerParam('project_id', $defaultProject['id']),
            'owner_id' => $this->request->getIntegerParam('owner_id'),
            'column_id' => $this->request->getIntegerParam('column_id'),
        );

        if ($values['column_id'] == 0) {
            $boardModel = new \Model\Board;
            $values['column_id'] = $boardModel->getFirstColumn($values['project_id']);
        }

        list($valid,) = $this->task->validateCreation($values);

        if ($valid && $this->task->create($values)) {
            $this->response->text('OK');
        }

        $this->response->text('FAILED');
    }

    // Show a task
    public function show()
    {
        $task = $this->task->getById($this->request->getIntegerParam('task_id'), true);

        if (! $task) $this->notfound();

        $this->checkProjectPermissions($task['project_id']);

        $values = $values = $this->request->getValues();
        $errors = $this->comment($values, $task['id']);
        $comments = $this->task->getCommentsByTask($task['id']);

        $this->response->html($this->template->layout('task_show', array(
            'task' => $task,
            'columns_list' => $this->board->getColumnsList($task['project_id']),
            'colors_list' => $this->task->getColors(),
            'menu' => 'tasks',
            'title' => $task['title'],
            'comments' => $comments,
            'errors' => $errors,
            'values' => $values
        )));
    }

    //add a comment
    public function comment(array $values, $task_id)
    {
        $errors = array();

        if ($_POST) {
            list($valid, $errors) = $this->task->validateComment($values);

            if ($valid) {
                $this->task->addComment(array(
                    'task_id' => $task_id,
                    'comment' => $values['comment'],
                    'user_id' => $this->acl->getUserId()
                ));
            }
        }

        return $errors;
    }

    // Display a form to create a new task
    public function create()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $this->checkProjectPermissions($project_id);

        $this->response->html($this->template->layout('task_new', array(
            'errors' => array(),
            'values' => array(
                'project_id' => $project_id,
                'column_id' => $this->request->getIntegerParam('column_id'),
                'color_id' => $this->request->getStringParam('color_id'),
                'owner_id' => $this->request->getIntegerParam('owner_id'),
                'another_task' => $this->request->getIntegerParam('another_task'),
            ),
            'projects_list' => $this->project->getListByStatus(\Model\Project::ACTIVE),
            'columns_list' => $this->board->getColumnsList($project_id),
            'users_list' => $this->project->getUsersList($project_id),
            'colors_list' => $this->task->getColors(),
            'menu' => 'tasks',
            'title' => t('New task')
        )));
    }

    // Validate and save a new task
    public function save()
    {
        $values = $this->request->getValues();
        $this->checkProjectPermissions($values['project_id']);

        list($valid, $errors) = $this->task->validateCreation($values);

        if ($valid) {

            if ($this->task->create($values)) {
                $this->session->flash(t('Task created successfully.'));

                if (isset($values['another_task']) && $values['another_task'] == 1) {
                    unset($values['title']);
                    unset($values['description']);
                    $this->response->redirect('?controller=task&action=create&'.http_build_query($values));
                }
                else {
                    $this->response->redirect('?controller=board&action=show&project_id='.$values['project_id']);
                }
            }
            else {
                $this->session->flashError(t('Unable to create your task.'));
            }
        }

        $this->response->html($this->template->layout('task_new', array(
            'errors' => $errors,
            'values' => $values,
            'projects_list' => $this->project->getListByStatus(\Model\Project::ACTIVE),
            'columns_list' => $this->board->getColumnsList($values['project_id']),
            'users_list' => $this->project->getUsersList($values['project_id']),
            'colors_list' => $this->task->getColors(),
            'menu' => 'tasks',
            'title' => t('New task')
        )));
    }

    // Display a form to edit a task
    public function edit()
    {
        $task = $this->task->getById($this->request->getIntegerParam('task_id'));

        if (! $task) $this->notfound();
        $this->checkProjectPermissions($task['project_id']);

        $this->response->html($this->template->layout('task_edit', array(
            'errors' => array(),
            'values' => $task,
            'columns_list' => $this->board->getColumnsList($task['project_id']),
            'users_list' => $this->project->getUsersList($task['project_id']),
            'colors_list' => $this->task->getColors(),
            'menu' => 'tasks',
            'title' => t('Edit a task')
        )));
    }

    // Validate and update a task
    public function update()
    {
        $values = $this->request->getValues();
        $this->checkProjectPermissions($values['project_id']);

        list($valid, $errors) = $this->task->validateModification($values);

        if ($valid) {

            if ($this->task->update($values)) {
                $this->session->flash(t('Task updated successfully.'));
                $this->response->redirect('?controller=task&action=show&task_id='.$values['id']);
            }
            else {
                $this->session->flashError(t('Unable to update your task.'));
            }
        }

        $this->response->html($this->template->layout('task_edit', array(
            'errors' => $errors,
            'values' => $values,
            'columns_list' => $this->board->getColumnsList($values['project_id']),
            'users_list' => $this->project->getUsersList($values['project_id']),
            'colors_list' => $this->task->getColors(),
            'menu' => 'tasks',
            'title' => t('Edit a task')
        )));
    }

    // Hide a task
    public function close()
    {
        $task = $this->task->getById($this->request->getIntegerParam('task_id'));

        if (! $task) $this->notfound();
        $this->checkProjectPermissions($task['project_id']);

        if ($this->task->close($task['id'])) {
            $this->session->flash(t('Task closed successfully.'));
        } else {
            $this->session->flashError(t('Unable to close this task.'));
        }

        $this->response->redirect('?controller=board&action=show&project_id='.$task['project_id']);
    }

    // Confirmation dialog before to close a task
    public function confirmClose()
    {
        $task = $this->task->getById($this->request->getIntegerParam('task_id'));

        if (! $task) $this->notfound();
        $this->checkProjectPermissions($task['project_id']);

        $this->response->html($this->template->layout('task_close', array(
            'task' => $task,
            'menu' => 'tasks',
            'title' => t('Close a task')
        )));
    }

    // Open a task
    public function open()
    {
        $task = $this->task->getById($this->request->getIntegerParam('task_id'));

        if (! $task) $this->notfound();
        $this->checkProjectPermissions($task['project_id']);

        if ($this->task->open($task['id'])) {
            $this->session->flash(t('Task opened successfully.'));
        } else {
            $this->session->flashError(t('Unable to open this task.'));
        }

        $this->response->redirect('?controller=board&action=show&project_id='.$task['project_id']);
    }

    // Confirmation dialog before to open a task
    public function confirmOpen()
    {
        $task = $this->task->getById($this->request->getIntegerParam('task_id'));

        if (! $task) $this->notfound();
        $this->checkProjectPermissions($task['project_id']);

        $this->response->html($this->template->layout('task_open', array(
            'task' => $task,
            'menu' => 'tasks',
            'title' => t('Open a task')
        )));
    }
}
