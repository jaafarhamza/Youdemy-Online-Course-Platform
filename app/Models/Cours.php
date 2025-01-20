<?php
namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use PDO;

abstract class Cours extends BaseModel
{
    protected $table = 'cours';
    protected $video_url;
    protected $content;
    protected $featured_image;
    protected $conn;

    public function __construct(PDO $db)
    {
        parent::__construct($db, $this->table);
        $this->conn = $db;
    }

    abstract public function displayContent();

    public function setVideoUrl($video_url)
    {
        $this->video_url = $video_url;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function addTagToCours($courseId, $tagId)
    {
        $stmt = $this->conn->prepare("INSERT INTO cours_tags (cours_id, tag_id) VALUES (:cours_id, :tag_id) ON DUPLICATE KEY UPDATE cours_id=cours_id");
        return $stmt->execute(['cours_id' => $courseId, 'tag_id' => $tagId]);
    }

        public function uploadFile($file)
    {
        return parent::uploadFile($file);
    }
}

class VideoCours extends Cours
{
    public function displayContent()
    {
        return "<video controls><source src='{$this->video_url}' type='video/mp4'></video>";
    }
}

class TextCours extends Cours
{
    public function displayContent()
    {
        return "<div>{$this->content}</div>";
    }
}
