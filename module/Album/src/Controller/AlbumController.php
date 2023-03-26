<?php

namespace Album\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Album\Entity\Album;
use Album\Form\AlbumForm;
use Doctrine\ORM\EntityManager;

class AlbumController extends AbstractActionController
{
  /**
   * @var DoctrineORMEntityManager
   */
  protected $em;

  public function __construct(EntityManager $em)
  {
    $this->em = $em;
  }

  public function indexAction()
  {
    return new ViewModel([
      'albums' => $this->em->getRepository('Album\Entity\Album')->findAll(),
    ]);
  }

  public function addAction()
  {
    $form = new AlbumForm();
    $form->get('submit')->setValue('Add');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $album = new Album();
      $form->setInputFilter($album->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $album->exchangeArray($form->getData());
        $this->em->persist($album);
        $this->em->flush();

        return $this->redirect()->toRoute('album');
      }
    }
    return array('form' => $form);
  }

  public function editAction()
  {
    $id = (int) $this->params()->fromRoute('id', 0);
    if (!$id) {
      return $this->redirect()->toRoute('album', array(
        'action' => 'add'
      ));
    }

    $album = $this->em->find('Album\Entity\Album', $id);
    if (!$album) {
      return $this->redirect()->toRoute('album', array(
        'action' => 'index'
      ));
    }

    $form  = new AlbumForm();
    $form->bind($album);
    $form->get('submit')->setAttribute('value', 'Edit');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($album->getInputFilter());
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $this->em->flush();

        return $this->redirect()->toRoute('album');
      }
    }

    return array(
      'id' => $id,
      'form' => $form,
    );
  }

  public function deleteAction()
  {
    $id = (int) $this->params()->fromRoute('id', 0);
    if (!$id) {
      return $this->redirect()->toRoute('album');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del', 'No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $album = $this->em->find('Album\Entity\Album', $id);
        if ($album) {
          $this->em->remove($album);
          $this->em->flush();
        }
      }

      return $this->redirect()->toRoute('album');
    }

    return array(
      'id'    => $id,
      'album' => $this->em->find('Album\Entity\Album', $id)
    );
  }
}
