<?php


class FeatureContext extends \Behatch\Context\RestContext
{
    /**
     * @var \App\DataFixtures\AppFixtures
     */
    private $fixtures;

    /**
     * @var \Coduo\PHPMatcher\Matcher
     */
    private $matcher;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    public function __construct(
        \Behatch\HttpCall\Request $request,
        \App\DataFixtures\AppFixtures $fixtures,
        \Doctrine\ORM\EntityManagerInterface $em
    ) {
        parent::__construct($request);
        $this->fixtures = $fixtures;
        $this->matcher =
            (new \Coduo\PHPMatcher\Factory\SimpleFactory())->createMatcher();
        $this->em = $em;
    }

    /**
     * @BeforeScenario @createSchema
     */
    public function createSchema(){

        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $fixturesExecutor =
            new \Doctrine\Common\DataFixtures\Executor\ORMExecutor(
                $this->em,
                $purger
            );

        $fixturesExecutor->execute([
            $this->fixtures
        ]);
    }

}
