<?php

return array(
    'BW' => function(\WideImage\Image $img, $data) {
        // GRAYSCALE
        $img->asGrayscale()->saveToFile($data['destination']);
    }
);
