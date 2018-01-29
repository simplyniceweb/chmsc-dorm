<?php
namespace forms\transformers;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\TaskBundle\Entity\Issue;

use Symfony\Component\HttpFoundation\File\File;

class StringToFileTransformer implements DataTransformerInterface
{
    
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    public function transform($filename)
    {
        if (null === $filename) {
            return "";
        }
        $pathToFile = UPLOAD_DIR . '/' . $filename;
        if(!file_exists($pathToFile)) {
            return null;
        } 
        $uploadFile = new File($pathToFile);
        
        return $uploadFile;
    }

    public function reverseTransform($file)
    {
        if (!isset($file) || empty($file)) {
            throw new TransformationFailedException(sprintf(
                'empty file %s',
                $file
            ));
            return;
        }
        $filename = $file->getClientOriginalName();

        $file->move(UPLOAD_DIR,$filename);

        if (null === $filename) {
            throw new TransformationFailedException(sprintf(
                'An file "%s" does not exist!',
                $file
            ));
        }

        return $filename;
    }
}