<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiPostController extends AbstractController
{
    /**
     * @Route("/api/post", name="api_post_index", methods={"get"})
     */
    public function index(PostRepository $postRepository, SerializerInterface $serilizer)
    {
        return $this->json($postRepository->findAll(),200,[],["groups" => "post:read"]);       
    }
    /**
     * @Route("/api/post", name="api_post_store", methods={"post"})
     */
    public function store(Request $request, SerializerInterface $serilizer,EntityManagerInterface $em,ValidatorInterface $validator)
    {
        $json = $request->getContent();
        try{
            $post = $serilizer->deserialize($json,Post::class,"json");
    
            $post->setCreatedAt(new \DateTime());
            
            $errors = $validator->validate($post);
            if(count($errors) > 0){
                return $this->json($errors,400);
            }
 
            $em->persist($post);
            $em->flush();

            return $this->json($post,201,[],["groups" => "post:read"]);
            dd($post);
        }
        catch(NotEncodableValueException $e){
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
