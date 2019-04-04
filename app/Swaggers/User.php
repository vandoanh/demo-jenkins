<?php

namespace UsingRefs;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 * )
 */
class User
{
    /**
     *  @OA\Property(
     *      format="int",
     *      description="ID",
     *      title="ID",
     *  )
     *
     *  @var integer
     */
    private $id;

    /**
     *  @OA\Property(
     *      description="Email",
     *      title="Email",
     *  )
     *
     *  @var string
     */
    private $email;

    /**
     *  @OA\Property(
     *      description="Fullname",
     *      title="Fullname",
     *  )
     *
     *  @var string
     */
    private $fullname;

    /**
     *  @OA\Property(
     *      description="Avatar",
     *      title="Avatar",
     *  )
     *
     *  @var string
     */
    private $avatar;

    /**
     *  @OA\Property(
     *      description="Gender",
     *      title="Gender",
     *  )
     *
     * @var int
     */
    private $gender;

    /**
     *  @OA\Property(
     *      description="Birthday",
     *      title="Birthday",
     *  )
     *
     * @var date
     */
    private $birthday;

    /**
     *  @OA\Property(
     *      description="Description",
     *      title="Description",
     *  )
     *
     *  @var string
     */
    private $description;

    /**
     *  @OA\Property(
     *      description="Timezone",
     *      title="Timezone",
     *  )
     *
     * @var string
     */
    private $timezone;

    /**
     *  @OA\Property(
     *      description="Receive notification",
     *      title="Receive notification",
     *  )
     *
     * @var int
     */
    private $receive_notification;
}
