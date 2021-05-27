<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Image\Metadata;

/**
 * Default metadata reader
 */
class DefaultMetadataReader extends AbstractMetadataReader
{
    /**
     * {@inheritdoc}
     */
    protected function extractFromFile($file)
    : array {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    protected function extractFromData($data)
    : array {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    protected function extractFromStream($resource)
    : array {
        return array();
    }
}
