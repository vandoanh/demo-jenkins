<?php

namespace UsingRefs;

/**
 * @OA\Schema(
 *     description="Response",
 *     title="Response"
 * )
 */
class Response
{
    /**
     *  @OA\Property(
     *      description="Status",
     *      title="Status",
     *      format="int32",
     *      default=1,
     *  )
     *
     *  @var integer
     */
    private $status;

    /**
     *  @OA\Property(
     *      description="Message",
     *      title="Message",
     *  )
     *
     * @var string
     */
    private $message;

    /**
     *  @OA\Property(
     *      description="Data",
     *      title="Data",
     *  )
     *
     *  @var object
     */
    private $data;
}
