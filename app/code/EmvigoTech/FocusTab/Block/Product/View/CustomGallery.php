<?php
namespace EmvigoTech\FocusTab\Block\Product\View;

use Magento\Framework\DataObject;


class CustomGallery extends \Magento\Catalog\Block\Product\View\Gallery
{
   public function getGalleryImagesJson()
    {
        
        $imagesItems = [];
        /** @var DataObject $image */
        foreach ($this->getGalleryImages() as $image) {
            $imageItem = new DataObject(
                [
                    'thumb' => $image->getData('small_image_url'),
                    'img' => $image->getData('medium_image_url'),
                    'full' => $image->getData('large_image_url'),
                    'caption' => ($image->getLabel() ?: $this->getProduct()->getName()),
                    // 'isMain'   => $this->isMainImage($image),
                    'position' => $image->getData('position'),
                    'type' => str_replace('external-', '', $image->getMediaType()),
                    'videoUrl' => $image->getVideoUrl(),
                ]
            );
            foreach ($this->getGalleryImagesConfig()->getItems() as $imageConfig) {
                $imageItem->setData(
                    $imageConfig->getData('json_object_key'),
                    $image->getData($imageConfig->getData('data_object_key'))
                );
            }
            $imagesItems[] = $imageItem->toArray();
        }
        if (empty($imagesItems)) {
            $imagesItems[] = [
                'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
                'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'caption' => '',
                'position' => '0',
                'type' => 'image',
                'videoUrl' => null,
            ];
        }
        return json_encode($imagesItems);
    }
}
