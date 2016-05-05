<?php
/**
 * PostType.php
 */

namespace Examples\Blog\Schema;

use Youshido\GraphQL\Type\Config\TypeConfigInterface;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class PostType extends AbstractObjectType
{

    public function build(TypeConfigInterface $config)
    {
        $config
            ->addField('oldTitle', new NonNullType(new StringType()), [
                'description'       => 'This field contains a post title',
                'isDeprecated'      => true,
                'deprecationReason' => 'field title is now deprecated',
                'args'              => [
                    'truncated' => new BooleanType()
                ],
                'resolve'           => function ($value, $args) {
                    return (!empty($args['truncated'])) ? explode(' ', $value)[0] . '...' : $value;
                }
            ])
            ->addField('title', new NonNullType(new StringType()))
            ->addField('status', new PostStatus())
            ->addField('summary', new StringType())
            ->addField('likeCount', new IntType());
        $config->addArgument('id', new IntType());
    }

    public function getInterfaces()
    {
        return [new ContentBlockInterface()];
    }

    public function resolve($value = null, $args = [], $type = null)
    {
        return [
            "title"     => "Post title from the PostType class",
            "summary"   => "This new GraphQL library for PHP works really well",
            "status"    => 1,
            "likeCount" => 2
        ];
    }

}