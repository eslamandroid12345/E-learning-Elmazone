<?php

namespace App\Http\Interfaces;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


interface CommentRepositoryInterface{

    public function videoAddComment(Request $request): JsonResponse;
    public function commentAddReplay(Request $request, $id): JsonResponse;
    public function updateComment(Request $request,$id): JsonResponse;
    public function deleteComment($id): JsonResponse;
    public function updateReplay(Request $request, $id): JsonResponse;
    public function deleteReplay($id): JsonResponse;

}
