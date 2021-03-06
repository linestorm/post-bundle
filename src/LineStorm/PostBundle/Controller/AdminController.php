<?php

namespace LineStorm\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends Controller
{
    public function listAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->get('linestorm.cms.model_manager');

        $posts = $modelManager->get('post')->findAll();

        return $this->render('LineStormPostBundle:Admin:list.html.twig', array(
            'posts' => $posts,
        ));
    }


    public function viewAction($id)
    {
        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('post');

        $modelManager = $this->get('linestorm.cms.model_manager');
        $post = $modelManager->get('post')->find($id);

        return $this->render('LineStormPostBundle:Admin:view.html.twig', array(
            'post'      => $post,
            'module'    => $module,
        ));
    }


    public function editAction($id)
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('post');

        $modelManager = $this->get('linestorm.cms.model_manager');
        $post = $modelManager->get('post')->find($id);

        $form = $this->createForm('linestorm_cms_form_post', $post, array(
            'action' => $this->generateUrl('linestorm_cms_module_post_api_put_post', array('id' => $post->getId())),
            'method' => 'PUT',
        ));

        return $this->render('LineStormPostBundle:Admin:edit.html.twig', array(
            'post'      => $post,
            'form'      => $form->createView(),
            'module'    => $module,
        ));
    }


    public function newAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('post');

        $modelManager = $this->get('linestorm.cms.model_manager');
        $post = $modelManager->create('post');

        $form = $this->createForm('linestorm_cms_form_post', $post, array(
            'action' => $this->generateUrl('linestorm_cms_module_post_api_post_post'),
            'method' => 'POST',
        ));

        return $this->render('LineStormPostBundle:Admin:new.html.twig', array(
            'post'      => null,
            'form'      => $form->createView(),
            'module'    => $module,
        ));
    }

}
