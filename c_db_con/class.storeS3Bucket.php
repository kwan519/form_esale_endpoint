<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class storeS3Bucket
{
    private $s3Client;
    private $bucket = 'alp-formfill-images';

    function __construct()
    {
        $this->s3Client = new S3Client([
            'profile' => 'default',
            'region' => 'us-west-2',
            'version' => '2006-03-01'
        ]);
    }

    function uploadImages($images = [], $site_id = '')
    {

        try {
            // Upload data.
            $urls = [];
            foreach ($images as $key => $image) {
                $result = $this->s3Client->putObject([
                    'Bucket' => $this->bucket,
                    'Key'    =>  $site_id.'/',
                    'ContentType' =>'image/jpg',
                    'Body'   => $image,
                    'ACL'    => 'public-read'
                ]);
                $urls[$key] = $result['ObjectURL'];
            }
        } catch (S3Exception $e) {
            echo $e->getMessage();
        }
    }
}
