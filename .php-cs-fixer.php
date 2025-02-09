<?php

$finder = (new PhpCsFixer\Finder())
	->in(__DIR__)
	->exclude('vendor')
	->exclude('var')
	->exclude('helm')
	->notName('bootstrap.php')
;

return (new PhpCsFixer\Config())
	->setFinder($finder)
	->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@PER-CS' => true,
    ]);

;
