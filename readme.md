## Use docker
- sh setup_docker.sh

## Normal
- Install nginx, php, mysql, nodejs & npm, redis, memcached, git, composer.
- Create database name eas_blog.
- sh setup.sh.

## Some command in docker
- List container: docker ps -a
- List container: docker rm [Conatiner]
- Stop all container: docker stop $(docker ps -a -q)
- Remove all container: docker rm $(docker ps -a -q)
- Access to a container/service: docker-compose exec [service] bash
- List image: docker images -a
- List image: docker rmi [Image]
- Remove all image: docker rmi $(docker images -a -q)
- Remove container, image, network: docker system prune -a
