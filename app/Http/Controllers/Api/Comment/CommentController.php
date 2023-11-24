<?php

namespace App\Http\Controllers\Api\Comment;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller{


    public CommentRepositoryInterface $commentRepositoryInterface;

    public function __construct(CommentRepositoryInterface $commentRepositoryInterface)
    {

       $this->commentRepositoryInterface = $commentRepositoryInterface;
    }

    public function videoAddComment(Request $request): JsonResponse
    {

      return $this->commentRepositoryInterface->videoAddComment($request);

    }

    public function commentAddReplay(Request $request, $id): JsonResponse
    {

        return $this->commentRepositoryInterface->commentAddReplay($request,$id);

    }

    public function updateComment(Request $request,$id): JsonResponse
    {

        return $this->commentRepositoryInterface->updateComment($request,$id);

    }

    public function deleteComment($id): JsonResponse
    {

        return $this->commentRepositoryInterface->deleteComment($id);

    }


    public function updateReplay(Request $request, $id): JsonResponse
    {

        return $this->commentRepositoryInterface->updateReplay($request,$id);

    }

    public function deleteReplay($id): JsonResponse
    {

        return $this->commentRepositoryInterface->deleteReplay($id);

    }
}
