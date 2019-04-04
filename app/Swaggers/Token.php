<?php

namespace UsingRefs;

/**
 * @OA\Schema(
 *     title="Token",
 *     description="Token model",
 * )
 */
class Token
{
    /**
     *  @OA\Property(
     *      description="Token type",
     *      title="Token type",
     *  )
     *
     *  @var string
     */
    private $token_type;

    /**
     *  @OA\Property(
     *      description="Expires in",
     *      title="Expires in",
     *  )
     *
     * @var int
     */
    private $expires_in;

    /**
     *  @OA\Property(
     *      description="Access token",
     *      title="Access token",
     *  )
     *
     *  @var string
     */
    private $access_token;

    /**
     *  @OA\Property(
     *      description="Refresh token",
     *      title="Refresh token",
     *  )
     *
     * @var string
     */
    private $refresh_token;
}
