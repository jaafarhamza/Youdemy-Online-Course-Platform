<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Models/Cours.php';

use App\Models\Categorie;
use App\Models\Tag;
use App\Models\TextCours;
use App\Models\VideoCours;
use PDO;

class CoursController
{
    private $categorieModel;
    private $tagModel;
    private $db;

    public function __construct(PDO $db)
    {
        $this->db             = $db;
        $this->categorieModel = new Categorie($db);
        $this->tagModel       = new Tag($db);
    }

    public function createCours($data, $files = [])
    {
        $title        = filter_input(INPUT_POST, 'title');
        $description  = filter_input(INPUT_POST, 'description');
        $category_id  = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $tags         = filter_input(INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        $content_type = filter_input(INPUT_POST, 'content_type');
        $video_url    = filter_input(INPUT_POST, 'video_url', FILTER_SANITIZE_URL);
        $content      = filter_input(INPUT_POST, 'content');

        $coursData = [
            'title'         => $title,
            'description'   => $description,
            'category_id'   => $category_id,
            'enseignant_id' => $_SESSION['id'],
            'status'        => 'draft',
        ];

        if ($content_type === 'video') {
            $coursData['video_url'] = $video_url;
            $cours                  = new VideoCours($this->db);
            $cours->setVideoUrl($video_url);
        } elseif ($content_type === 'text') {
            $coursData['content'] = $content;
            $cours                = new TextCours($this->db);
            $cours->setContent($content);
        } else {
            echo "Invalid content type.";
            return;
        }

        $coursId = $cours->create($coursData);

        //  image upload
        if (isset($files['featured_image']) && $files['featured_image']['error'] === UPLOAD_ERR_OK) {
            $filePath = $cours->uploadFile($files['featured_image']);
            if ($filePath) {
                $cours->update(['featured_image' => $filePath], ['id' => $coursId]);
            } else {
                echo "Image upload failed.";
            }
        }

        if ($coursId) {
            foreach ($tags as $tagId) {
                $cours->addTagToCours($coursId, $tagId);
            }
            header('Location: ../cours/index.php');
            exit;
        } else {
            echo "Failed to create cours.";
        }
    }

    public function readCategories()
    {
        return $this->categorieModel->read();
    }

    public function readTags()
    {
        return $this->tagModel->read();
    }

    public function displaycours($coursId)
    {
        $coursData = $this->categorieModel->read(['id' => $coursId]);
        if (! empty($coursData)) {
            $coursData = $coursData[0];
            if (! empty($coursData['video_url'])) {
                $cours = new VideoCours($this->db);
                $cours->setVideoUrl($coursData['video_url']);
            } elseif (! empty($coursData['content'])) {
                $cours = new TextCours($this->db);
                $cours->setContent($coursData['content']);
            }
            return $cours->displayContent();
        }
        return "cours not found.";
    }
    public function getAllCourses()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $categoryCours = [];
    
        foreach ($categories as $category) {
            $categoryId = $category['id'];
    
            $stmt = $this->db->prepare("SELECT * FROM cours WHERE category_id = :category_id");
            $stmt->execute(['category_id' => $categoryId]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $categoryCours[$categoryId] = [
                'name'  => $category['name'],
                'cours' => $courses, 
            ];
        }
        return $categoryCours;
    }

}
