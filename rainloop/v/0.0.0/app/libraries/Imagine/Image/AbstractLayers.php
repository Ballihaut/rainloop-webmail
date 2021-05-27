<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Image;

abstract class AbstractLayers implements LayersInterface
{
    /**
     * {@inheritdoc}
     */
    public function add(ImageInterface $image)
    : self {
        $this[] = $image;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($offset, ImageInterface $image)
    : self {
        $this[$offset] = $image;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($offset)
    : self {
        unset($this[$offset]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($offset)
    {
        return $this[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function has($offset)
    {
        return isset($this[$offset]);
    }
}
