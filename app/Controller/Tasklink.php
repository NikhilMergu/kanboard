<?php

namespace Controller;

use Model\Task AS TaskModel;
/**
 * TaskLink controller
 *
 * @package  controller
 * @author   Olivier Maridat
 */
class Tasklink extends Base
{
    /**
     * Get the current link
     *
     * @access private
     * @return array
     */
    private function getTaskLink()
    {
        $link = $this->taskLink->getById($this->request->getIntegerParam('link_id'));
        if (! $link) {
            $this->notfound();
        }
        return $link;
    }

    /**
     * Creation form
     *
     * @access public
     */
    public function create(array $values = array(), array $errors = array())
    {
        $task = $this->getTask();

        if (empty($values)) {
            $values = array(
                'task_id' => $task['id'],
                'another_link' => $this->request->getIntegerParam('another_link', 0)
            );
        }

        $this->response->html($this->taskLayout('tasklink/edit', array(
            'values' => $values,
            'errors' => $errors,
            'link_list' => $this->link->getLinkLabelList($task['project_id']),
            'task_list' => $this->taskFinder->getList($task['project_id'], TaskModel::STATUS_OPEN, $task['id']),
            'task' => $task,
        )));
    }

    /**
     * Validation and creation
     *
     * @access public
     */
    public function save()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->taskLink->validateCreation($values);

        if ($valid) {
            if ($this->taskLink->create($values)) {
                $this->session->flash(t('Link added successfully.'));
                if (isset($values['another_link']) && $values['another_link'] == 1) {
                    $this->response->redirect('?controller=tasklink&action=create&task_id='.$task['id'].'&project_id='.$task['project_id'].'&another_link=1');
                }
                
                $this->response->redirect('?controller=task&action=show&task_id='.$task['id'].'&project_id='.$task['project_id'].'#links');
            }
            else {
                $this->session->flashError(t('Unable to add the link.'));
            }
        }

        $this->create($values, $errors);
    }

    /**
     * Edit form
     *
     * @access public
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $task = $this->getTask();
        $taskLink = $this->getTaskLink();

        $this->response->html($this->taskLayout('tasklink/edit', array(
            'values' => empty($values) ? $taskLink : $values,
            'errors' => $errors,
            'link_list' => $this->link->getLinkLabelList($task['project_id'], false),
        	'task_list' => $this->taskFinder->getList($task['project_id'], TaskModel::STATUS_OPEN, $task['id']),
            'link' => $taskLink,
            'task' => $task,
            'edit' => true,
        )));
    }

    /**
     * Update and validate a link
     *
     * @access public
     */
    public function update()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->taskLink->validateModification($values);

        if ($valid) {
            if ($this->taskLink->update($values)) {
                $this->session->flash(t('Link updated successfully.'));
                $this->response->redirect('?controller=task&action=show&task_id='.$task['id'].'&project_id='.$task['project_id'].'#links');
            }
            else {
                $this->session->flashError(t('Unable to update the link.'));
            }
        }
        $this->edit($values, $errors);
    }

    /**
     * Confirmation dialog before removing a link
     *
     * @access public
     */
    public function confirm()
    {
        $task = $this->getTask();
        $link = $this->getTaskLink();
        $this->response->html($this->taskLayout('tasklink/remove', array(
            'link' => $link,
            'task' => $task,
        )));
    }

    /**
     * Remove a link
     *
     * @access public
     */
	public function remove()
    {
        $this->checkCSRFParam();
        $task = $this->getTask();
        
        if ($this->taskLink->remove($this->request->getIntegerParam('link_id'))) {
            $this->session->flash(t('Link removed successfully.'));
        	$this->response->redirect('?controller=task&action=show&task_id='.$task['id'].'&project_id='.$task['project_id'].'#links');
        }
        else {
            $this->session->flashError(t('Unable to remove this link.'));
        }
        $this->confirm();
    }
}
