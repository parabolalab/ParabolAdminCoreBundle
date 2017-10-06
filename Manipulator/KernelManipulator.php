<?php

namespace Parabol\AdminCoreBundle\Manipulator;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Changes the PHP code of a Kernel.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class KernelManipulator extends \Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator
{
    protected $kernel;
    protected $reflected;

    /**
     * Adds a bundle at the end of the existing ones.
     *
     * @param string $bundle The bundle class name
     *
     * @return bool true if it worked, false otherwise
     *
     * @throws \RuntimeException If bundle is already defined
     */
    public function addBundles($bundles)
    {
        if (!$this->reflected->getFilename()) {
            return false;
        }

        $src = file($this->reflected->getFilename());
        $method = $this->reflected->getMethod('registerBundles');
        $lines = array_slice($src, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1);

        foreach($bundles as $bundle)
        {
            // Don't add same bundle twice
            if (false !== strpos(implode('', $lines), $bundle)) {
                throw new \RuntimeException(sprintf('Bundle "%s" is already defined in "AppKernel::registerBundles()".', $bundle));
            }
        }

        $this->setCode(token_get_all('<?php '.implode('', $lines)), $method->getStartLine());
        while ($token = $this->next()) {
            // $bundles

            if (T_VARIABLE !== $token[0] || '$bundles' !== $token[1]) {
                continue;
            }


            // =
            $this->next();

            // array start with traditional or short syntax
            $token = $this->next();
            if (T_ARRAY !== $token[0] && '[' !== $this->value($token)) {
                return false;
            }

            // add the bundle at the end of the array
            while ($token = $this->next()) {
                // look for ); or ];
                // echo  "\ntoken:" . $this->value($token);
                if (')' !== $this->value($token) && ']' !== $this->value($token)) {
                    continue;
                }

                if (';' !== $this->value($this->peek())) {
                    continue;
                }

                // ;
                $this->next();
                $bundleLines = array();
                foreach($bundles as $bundle) $bundleLines[] = sprintf("            new %s,\n", ($bundle . substr($bundle, -1) == ')' ? '' : '()'));
                $lines = array_merge(
                    array_slice($src, 0, $this->line - 2),
                    // Appends a separator comma to the current last position of the array
                    array(rtrim(rtrim($src[$this->line - 2]), ',').",\n"),
                    $bundleLines,
                    array_slice($src, $this->line - 1)
                );

                file_put_contents($this->reflected->getFilename(), implode('', $lines));

                return true;
            }
        }
    }
}
