<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'locale'            => 'en_GB',
    'apis'              => [
        'article'       => getenv('MICROSERVICE_ARTICLE_URL'),
    ],
    'googleAnalytics'   => getenv('USE_GOOGLE_ANALYTICS'),
    'aws' => [
        'key'           => getenv('AWS_KEY'),
        'secret'        => getenv('AWS_SECRET'),
        'associateTag'  => getenv('AWS_ASSOCIATE_TAG'),
        'categories'    => [
            283926,       // blu-ray & dvd
            293962011,    // blu-ray
            4188180031,   // BAFTA 2014
            4192569031,   // Academy Awards 2014
            501976,       // Classics
        ],
    ],
];
