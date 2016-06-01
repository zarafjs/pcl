<?php
namespace pcl\Controller;

use Pcl\Controller\AppController;
use Cake\Event\Event;

/**
 * Access Controller
 *
 */
class AccessController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['sync']);
    }

    public function groups()
    {

        $this->loadModel('Groups');

        $groups = $this->Groups->find();
        $this->set(compact('groups'));

        $pclAcoList = \Cake\Core\Configure::read('pclAcoList');
        $displayList = is_array($pclAcoList) ? $pclAcoList : [];
        if ($displayList) {
            $displayList[] = 'controllers';
        }


        $defaultIgnoreList = ['Acl', 'Migrations', 'DebugKit', 'Pcl', 'Papa'];

        $this->loadModel('Acos');
        $query = $this->Acos->find('threaded');
        $query->contain('Aros');
        $query->where(['alias NOT LIKE' => "ex%"]);
        $query->where(['alias NOT IN' => $defaultIgnoreList]);
        if ($displayList) {
            $query->where(['alias IN' => $displayList]);
        }

        $acos = $query->toArray();

        $this->set(compact('acos'));

    }

    public function sync()
    {
        if ($this->request->is(['post'])) {
            $job = new \Acl\Shell\AclExtrasShell();
            $job->startup();
            $job->acoSync();
            return $this->redirect(['action' => 'groups']);
        }
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
