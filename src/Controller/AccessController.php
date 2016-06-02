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

        $this->loadModel('Acos');
        $query = $this->Acos->find('threaded');
        $query->contain('Aros');
        $query->where(['included' => 1]);
        $results = $query->toArray();
        if (isset($results[0]->alias) && $results[0]->alias == 'controllers') {
            $acos = $results[0];
            $this->set(compact('acos'));
        }
    }

    public function sync()
    {
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;

        if ($this->request->is(['post'])) {
            $job = new \Acl\Shell\AclExtrasShell();
            $job->startup();
            $job->acoSync();
            return $this->redirect($this->referer());
        }

        $this->loadModel('Acos');
        $aco = $this->Acos->find()->first();

        if ($aco) {
            if (!isset($aco->included)) {
                try {
                    \Cake\Cache\Cache::clear(false, '_cake_model_');
                    $sql = "ALTER TABLE `acos` ADD `included` BOOLEAN NULL DEFAULT TRUE AFTER `rght`";
                    $conn = \Cake\Datasource\ConnectionManager::get('default');
                    $conn->execute($sql);

                } catch (\Cake\Core\Exception\Exception $ex) {
                    die($ex->getMessage());
                }
            }
        }
        $job = new \Acl\Shell\AclExtrasShell();
        $job->startup();
        $job->acoSync();
        return $this->redirect(['action' => 'inclusions']);
    }

    public function inclusions()
    {
        $this->loadModel('Acos');
        $query = $this->Acos->find('threaded');
        $results = $query->toArray();
        if (isset($results[0]->alias) && $results[0]->alias == 'controllers') {
            $acos = $results[0];
            $this->set(compact('acos'));
        }
    }

    public function changePermission()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;

        $aro_aco = $this->request->data['aro_aco'];
        $aroAcoArr = explode('_', $aro_aco);
        $aroId = $aroAcoArr[0];
        $acoId = $aroAcoArr[1];

        $currentlyDenied = $this->request->data['currentlyDenied'];

        if ($currentlyDenied == 1) {
            $this->Acl->allow($aroId, $acoId);
            exit('allowed');
        } else {
            $this->Acl->deny($aroId, $acoId);
            exit('denied');
        }

        exit('');
    }

    public function changeInclusion()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->layout(false);
        $this->autoRender = false;

        $acoId = $this->request->data['acoId'];
        $currentlyExcluded = $this->request->data['currentlyExcluded'];

        $this->loadModel('Acos');
        $aco = $this->Acos->get($acoId);

        if ($aco) {
            if ($currentlyExcluded == 1) {
                $aco->included = 1;
                $this->Acos->save($aco);
                exit('included');
            } else {
                $aco->included = 0;
                $this->Acos->save($aco);
                exit('excluded');
            }
        }

        exit('');
    }
}
