<?php

namespace common\components\helpers;

use Location\Bearing\BearingEllipsoidal;
use Location\Bearing\BearingSpherical;
use Location\Coordinate;

class GpsBounce
{
    function __construct()
    {
    }

    /**
     * @var Coordinate $north
     */
    public $north;
    /**
     * @var Coordinate $east
     */
    public $east;
    /**
     * @var Coordinate $south
     */
    public $south;
    /**
     * @var Coordinate $west
     */
    public $west;
}

class GpsBounceCalculator
{

    private $_sourceCoordinate;
    private $_radius;

    /**
     * @var GpsBounce $gpsBonce
     */
    public $gpsBonce;

    /**
     * GpsBounceCalculator constructor.
     * @param Coordinate $coordinate
     * @param int $radius
     */
    function __construct($coordinate, $radius)
    {
        $this->_sourceCoordinate = $coordinate;
        $this->_radius = $radius;
        $this->gpsBonce = new GpsBounce();
    }

    /**
     * @return GpsBounce
     */
    function calculate()
    {
        $bearingSpherical = new BearingSpherical();
        $bearingEllipsoidal = new BearingEllipsoidal();
        $this->gpsBonce->north = $bearingEllipsoidal->calculateDestination($this->_sourceCoordinate, 0, $this->_radius);
        $this->gpsBonce->east = $bearingEllipsoidal->calculateDestination($this->_sourceCoordinate, 90, $this->_radius);
        $this->gpsBonce->south = $bearingEllipsoidal->calculateDestination($this->_sourceCoordinate, 180, $this->_radius);
        $this->gpsBonce->west = $bearingEllipsoidal->calculateDestination($this->_sourceCoordinate, 270, $this->_radius);
        return $this->gpsBonce;
    }

}

