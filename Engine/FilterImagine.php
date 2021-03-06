<?php


namespace Clooder\ImagineBundle\Engine;

use Clooder\ImagineBundle\Manager\ImagineManager;
use Symfony\Component\Filesystem\Filesystem;


/**
 * Class FilterImagine
 * @package Clooder\ImagineBundle\Engine
 */
class FilterImagine
{

    private $imagineManger;
    private $temporaryDownloadFile;
    private $fs;

    public function __construct(ImagineManager $imagineManger, Filesystem $filesystem)
    {
        $this->imagineManger = $imagineManger;
        $this->fs            = $filesystem;
    }

    public function getResolver($pathFile, $filterUsing)
    {

        $this->imagineManger->setFile($this->pathFileAccessiblity($pathFile));

        $this->imagineManger->setFilter($filterUsing);

        return $this->imagineManger;
    }


    private function pathFileAccessiblity($path)
    {
        $this->temporaryDownloadFile = sys_get_temp_dir();
        $urlParse = parse_url($path);
        $extension = pathinfo($urlParse['path'])['extension'];
        if (isset($urlParse['scheme'])) {
            $fileTmp = $this->temporaryDownloadFile . '/image_' . md5($path) . '.' . $extension;
            if (!$this->fs->exists($fileTmp)) {
                file_put_contents($fileTmp,file_get_contents($path));
            }

            return $fileTmp;
        } else {
            return $path;
        }
    }
}
