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

    public function getAllCourses($categoryId, $page, $limit)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->db->prepare("SELECT 
            c.id AS course_id,
            c.title,
            c.description,
            c.featured_image,
            c.created_at,
            v.username AS enseignant_name,
            GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
        FROM 
            cours c
        JOIN 
            visiteur v ON c.enseignant_id = v.id
        LEFT JOIN 
            cours_tags ct ON c.id = ct.cours_id
        LEFT JOIN 
            tags t ON ct.tag_id = t.id
        WHERE 
            c.category_id = :categoryId
            AND c.status = 'published'
        GROUP BY 
            c.id
        LIMIT :limit OFFSET :offset;");

        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCourses($categoryId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cours WHERE category_id = :categoryId");
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function enrollInCourse($cours_id, $etudiant_id)
    {
        $stmt = $this->db->prepare("INSERT INTO cours_etudiants (cours_id, etudiant_id) VALUES (:cours_id, :etudiant_id)");
        $stmt->bindValue(':cours_id', $cours_id, PDO::PARAM_INT);
        $stmt->bindValue(':etudiant_id', $etudiant_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function editCours($coursId, $data, $files = [])
    {

        $coursData = $this->getCourseById($coursId);
        if (empty($coursData)) {
            return "Course not found.";
        }

        if (!empty($coursData['video_url'])) {
            $cours = new VideoCours($this->db);
            $cours->setVideoUrl($coursData['video_url']);
        } elseif (!empty($coursData['content'])) {
            $cours = new TextCours($this->db);
            $cours->setContent($coursData['content']);
        }

        $title = filter_input(INPUT_POST, 'title');
        $description = filter_input(INPUT_POST, 'description');
        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $tags = filter_input(INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        $content_type = filter_input(INPUT_POST, 'content_type');
        $video_url = filter_input(INPUT_POST, 'video_url', FILTER_SANITIZE_URL);
        $content = filter_input(INPUT_POST, 'content');

        $updateData = [
            'title' => $title,
            'description' => $description,
            'category_id' => $category_id,
        ];

        if ($content_type === 'video') {
            $updateData['video_url'] = $video_url;
            $cours->setVideoUrl($video_url);
        } elseif ($content_type === 'text') {
            $updateData['content'] = $content;
            $cours->setContent($content);
        }

        if ($cours->update($updateData, ['id' => $coursId])) {

            if (isset($files['featured_image']) && $files['featured_image']['error'] === UPLOAD_ERR_OK) {
                $filePath = $cours->uploadFile($files['featured_image']);
                if ($filePath) {
                    $cours->update(['featured_image' => $filePath], ['id' => $coursId]);
                } else {
                    echo "Image upload failed.";
                }
            }

            $this->updateTags($coursId, $tags);

            header('Location: ../cours/index.php');
            exit;
        } else {
            echo "Failed to update course.";
        }
    }

    private function getCourseById($coursId)
    {
        $stmt = $this->db->prepare("SELECT * FROM cours WHERE id = :id");
        $stmt->bindValue(':id', $coursId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function updateTags($coursId, $tags)
    {
        $stmt = $this->db->prepare("DELETE FROM cours_tags WHERE cours_id = :cours_id");
        $stmt->bindValue(':cours_id', $coursId, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($tags as $tagId) {
            $stmt = $this->db->prepare("INSERT INTO cours_tags (cours_id, tag_id) VALUES (:cours_id, :tag_id)");
            $stmt->bindValue(':cours_id', $coursId, PDO::PARAM_INT);
            $stmt->bindValue(':tag_id', $tagId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    public function getAllCoursesForAdmin($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->db->prepare("
        SELECT 
            c.id AS course_id,
            c.title,
            c.status
        FROM 
            cours c
        LIMIT :limit OFFSET :offset
    ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCourseStatus($courseId, $newStatus)
    {
        // Validate the new status
        $allowedStatuses = ['draft', 'published', 'scheduled'];
        if (!in_array($newStatus, $allowedStatuses)) {
            return "Invalid status.";
        }

        // Update the course status
        $stmt = $this->db->prepare("UPDATE cours SET status = :status WHERE id = :id");
        $stmt->bindValue(':status', $newStatus, PDO::PARAM_STR);
        $stmt->bindValue(':id', $courseId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true; // Success
        } else {
            return false; // Failed
        }
    }

    public function getTotalTags()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tags");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalCategories()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalCoursesStatis()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cours WHERE status = 'published'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalStudents()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM visiteur WHERE role = 'etudiant'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalEnseignants()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM visiteur WHERE role = 'enseignant'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getCoursesByCategory()
    {
        $stmt = $this->db->prepare("
        SELECT c.name AS category_name, COUNT(cours.id) AS course_count
        FROM categories c
        LEFT JOIN cours ON c.id = cours.category_id
        WHERE cours.status = 'published' 
        GROUP BY c.name
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopEnseignants()
    {
        $stmt = $this->db->prepare("
        SELECT v.username AS enseignant_name, COUNT(c.id) AS course_count
        FROM visiteur v
        LEFT JOIN cours c ON v.id = c.enseignant_id
        WHERE c.status = 'published' 
        GROUP BY v.username
        ORDER BY course_count DESC
        LIMIT 3
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopCoursesByStudents()
    {
        $stmt = $this->db->prepare("
        SELECT c.title AS course_title, COUNT(ce.etudiant_id) AS student_count
        FROM cours c
        LEFT JOIN cours_etudiants ce ON c.id = ce.cours_id
        WHERE c.status = 'published' 
        GROUP BY c.title
        ORDER BY student_count DESC
        LIMIT 3
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchCourses($query)
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.id AS course_id,
                c.title,
                c.description,
                c.featured_image,
                c.created_at,
                v.username AS enseignant_name,
                GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
            FROM 
                cours c
            JOIN 
                visiteur v ON c.enseignant_id = v.id
            LEFT JOIN 
                cours_tags ct ON c.id = ct.cours_id
            LEFT JOIN 
                tags t ON ct.tag_id = t.id
            WHERE 
                (c.title LIKE :query OR c.description LIKE :query OR v.username LIKE :query)
                AND c.status = 'published' -- Only search published courses
            GROUP BY 
                c.id
        ");
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
