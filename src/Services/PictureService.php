<?php
namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

Class PictureService
{
    private $params;
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        
    }

    public function add(UploadedFile $picture, ?string $folder='', ?int $width=250, ?int $higth=250)
    {
        // on donne un nouveau nom à l'image
        $ficher = md5(uniqid(rand(), true)).'.jpg';

        // on recupere les infos de l'image
        $picture_info = getimagesize($picture);

        if($picture_info ===false){

            throw new Exception('Format d\'image incorrecte');

        }

        // on verifie le format de l'image

        switch($picture_info['mine'])
        {
            case 'image/jpg':
                $picture_source = imagecreatefromjpeg($picture);
                break;

            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            
            default:
                throw new Exception('Format d\'image incorrecte');      
                   
        }
        // on recadre l'image
        //on récupère les dimensions

        $imageWidth = $picture_info[0];
        $imageHigth = $picture_info[1];

        // on vérifie l'orientation 

        switch($imageWidth<=>$imageHigth)
        {
            case -1: // profile
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHigth - $squareSize)/2;
                break;

            case 0: // carre
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            
            case 1: // paysage
                $squareSize = $imageHigth;
                $src_x = ( $imageWidth- $squareSize)/2;
                $src_y = 0;
                break;
                    
        }

        $resized_picture = imagecreatetruecolor($width,$higth);

        imagecopyresampled($resized_picture, $picture_source,0, 0, $src_x, $src_y, $width, $higth,$squareSize, $squareSize );

        $path = $this->params->get('images_directory').$folder;

        // on crée le dossier de destination s'il n'existe pas 

        if( !file_exists($path .'/mini/'))
        {
            mkdir($path .'/mini/',0755,true);

        }

        //on stoke l'image

        imagejpeg($resized_picture,$path .'/mini/'.$width .'x'.$higth .'-'.$ficher);
        $picture->move($path .'/', $ficher);

        return $ficher;


    }

    public function delete(?string $ficher, ?string $folder='', ?int $width=250, ?int $higth=250)

    {
        if( $ficher !== 'default.jpeg')
        {
            $success = false;
            $path = $this->params->get('images_directory').$folder;
            $mini = $path .'/mini/'.$width .'x'.$higth .'-'.$ficher;
            if(file_exists($mini))
            {
                unlink($mini);
                $success = true;

            }

            $original = $path.'/'.$ficher;

            if(file_exists($original))
            {
                unlink($mini);
                $success = true;

            }

            return $success ;

        }
        return false;
    }
}