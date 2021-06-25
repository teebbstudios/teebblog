<?php

namespace App\Controller;

use App\Factory\PostFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test/{name}', name: 'test')]
    public function index(Request $request, string $name ="Quan Weiwei"): Response
    {
//        $name = $request->attributes->get('name');
        //dd($request);
        //$page = $request->query->getInt('page', 1);
        //$name = $request->query->get('name');

        return new Response(<<<EOF
<html>
<head>
<title>这是我们的第一个页面</title>
</head>
<body>
<h1>$name</h1>
</body>
</html>
EOF
);
    }
}
