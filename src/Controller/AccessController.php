<?php
namespace Pcl\Controller;

use Pcl\Controller\AppController;

/**
 * Access Controller
 *
 */
class AccessController extends AppController
{

    public function groups()
    {

        $this->loadModel('Groups');

        $groups = $this->Groups->find();
        $this->set(compact('groups'));

        $inArray = ['controllers', 'Tasks', 'Contacts', 'Organizations', 'Leads', 'Opportunities', 'Projects', 'Settings', 'add', 'edit', 'view', 'delete'];

        $this->loadModel('Acos');
        $query = $this->Acos->find('threaded')
            ->contain('Aros')
            ->where(['alias IN ' => $inArray]);
        $acos = $query->toArray();

        $this->set(compact('acos'));

    }

    public function exChangePermission()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;

        $aro_aco = $this->request->data['aro_aco'];
        $aroAcoArr = explode('_', $aro_aco);
        $aroId = $aroAcoArr[0];
        $acoId = $aroAcoArr[1];

        $currentlyDenied = $this->request->data['currentlyDenied'];

        $success = false;
        if ($currentlyDenied == 1) {
            $allowed = $this->Acl->allow($aroId, $acoId);
            exit('allowed');
        } else {
            $denied = $this->Acl->deny($aroId, $acoId);
            exit('denied');
        }

        exit('');
    }
}
