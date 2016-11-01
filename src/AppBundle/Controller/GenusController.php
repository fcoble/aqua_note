<?php
/**
 * Created by PhpStorm.
 * User: fcoble
 * Date: 10/27/16
 * Time: 10:54 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Genus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{

    /**
     * @Route("/genus/new")
     */
    public function newAction(){

        $genus = new Genus();
        $genus->setName('Octopus'.rand(0,100));
        $genus->setSubFamily('Octopdinae');
        $genus->setSpeciesCount(rand(100,99999));

        $em = $this->getDoctrine()->getManager();

        $em->persist($genus);
        $em->flush();

        return new response('<html><body>Genus Created</body></html>');
    }

    /**
     * @Route("/genus")
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAll();

        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses,
        ]);
    }

    /**
     * @Route("/genus/{genusName}", name="genus_show"
     * )
     */
    public function showAction($genusName){

        /*
        $templating = $this->container->get('templating');
        $html = $templating->render('genus/show.html.twig', [
            'name' => $genusName
        ]);

        return new Response($html);
        */

        //The above code shows what is actually happening. The below code is a shortcut

        $funFact =   'Octopuses can change the color of their body in just *three-tenths* of a second!';

        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        $key = md5($funFact);

        if ($cache->contains($key)) {
            $funFact = $cache->fetch($key);
        } else {
            sleep(1);
            $funFact = $this->get('markdown.parser')
                ->transform($funFact);
            $cache->save($key, $funFact);
        }

        $funFact = $this->get('markdown.parser')
            ->transform($funFact);

        return $this->render('genus/show.html.twig', [
            'name' => $genusName,
            'funFact' => $funFact,
        ]);

    }

    /**
     * @Route("/genus/{genusName}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction() {

        $notes = [
            ['id' => 'username', 'avatarUri' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle','date' => 'Dec. 10, 2015'],
            ['id' => 'username2', 'avatarUri' => 'AquaPelham', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 egs... as they wrapped around me','date' => 'Dec. 1, 2015'],
            ['id' => 'username3', 'avatarUri' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked','date' => 'Aug. 20, 2015']
        ];

        $data = [
            'notes' => $notes
        ];

        //return new Response(json_encode($data));
        //use the below for easier response and to include json type in the response header
        return new JsonResponse($data);

    }


}