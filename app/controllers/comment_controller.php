<?php
require_once 'app/models/CommentModel.php';

class CommentController extends Controller
{
    public function save()
    {
        header('Content-Type: application/javascript; charset=utf-8');

        $responseXml = $this->buildResponseXml('error', 'Неизвестная ошибка.');

        if (!isset($_SESSION['user_id'])) {
            $this->outputScriptResponse(
                $this->buildResponseXml('error', 'Только авторизованные пользователи могут оставлять комментарии.')
            );
            exit;
        }

        $xmlPayload = $_GET['xml'] ?? '';
        if ($xmlPayload === '') {
            $this->outputScriptResponse(
                $this->buildResponseXml('error', 'Не получены данные комментария.')
            );
            exit;
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlPayload);

        if ($xml === false) {
            $this->outputScriptResponse(
                $this->buildResponseXml('error', 'Не удалось разобрать XML.')
            );
            exit;
        }

        $postId = (int)($xml->post_id ?? 0);
        $message = trim((string)($xml->message ?? ''));

        if ($postId <= 0) {
            $this->outputScriptResponse(
                $this->buildResponseXml('error', 'Не удалось определить запись блога.')
            );
            exit;
        }

        if ($message === '') {
            $this->outputScriptResponse(
                $this->buildResponseXml('error', 'Комментарий не может быть пустым.')
            );
            exit;
        }

        $comment = new CommentModel();
        $comment->blog_post_id = $postId;
        $comment->user_id = (int)$_SESSION['user_id'];
        $comment->author_name = $_SESSION['user_name'] ?? 'Пользователь';
        $comment->message = $message;
        $comment->save();

        $saved = CommentModel::find($comment->id);

        $responseXml = $this->buildResponseXml('success', 'Комментарий сохранён.', $saved);
        $this->outputScriptResponse($responseXml);
        exit;
    }

    private function outputScriptResponse($responseXml)
    {
        echo 'window.blogCommentCallback(' .
             json_encode($responseXml, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
             ');';
    }

    private function buildResponseXml($status, $message, $comment = null)
    {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $root = $xml->createElement('response');
        $xml->appendChild($root);

        $root->appendChild($xml->createElement('status', $status));
        $root->appendChild($xml->createElement('message', $message));

        if ($comment) {
            $commentNode = $xml->createElement('comment');
            $commentNode->appendChild($xml->createElement('id', (string)$comment->id));
            $commentNode->appendChild($xml->createElement('post_id', (string)$comment->blog_post_id));
            $commentNode->appendChild($xml->createElement('author', $comment->author_name));
            $commentNode->appendChild($xml->createElement('created_at', $comment->getFormattedDate()));

            $textNode = $xml->createElement('text');
            $textNode->appendChild($xml->createTextNode($comment->message));
            $commentNode->appendChild($textNode);

            $root->appendChild($commentNode);
        }

        return $xml->saveXML();
    }
}