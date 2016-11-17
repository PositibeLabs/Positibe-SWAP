<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Zap;

use AppBundle\Entity\Test;
use Doctrine\ORM\EntityManager;
use Zap\ZapError;
use Zap\Zapv2;


/**
 * Class ZapService
 * @package AppBundle\Zap
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ZapService
{
    protected $spiderScanMax = 3;
    protected $zap;
    protected $key;
    protected $manager;

    /**
     * @param EntityManager $manager
     * @param $key
     * @param string $zapApiHost
     * @throws ZapError
     */
    public function __construct(EntityManager $manager, $key, $zapApiHost = 'tcp://localhost:8080')
    {
        $this->manager = $manager;
        $this->key = $key;
        $this->zap = new Zapv2($zapApiHost);
        $this->zap->base = 'http://localhost:8080/JSON/';
        try {
            $this->zap->core->version();
        } catch (\Exception $e) {
            throw new ZapError(
              sprintf('No se pudo conectar con el servidor zap por el host "%s" y la llave %s', $zapApiHost, $key)
            );
        }
    }

    /**
     * @return Zapv2
     */
    public function getZap()
    {
        return $this->zap;
    }

    public function startNewScan(Test $test)
    {
//        $this->zap->core->newSession($test->getSession(), '', $this->key);
        $id = $this->zap->spider->scan($test->getSite(), $this->spiderScanMax, $this->key);
        $test->addSpiderScan($id);
//        $this->zap->core->saveSession($test->getSession(), '', $this->key);

        $this->manager->persist($test);
        $this->manager->flush();
    }


    public function updateSpiderScanStatus(Test $test)
    {
        $percent = intval($this->zap->spider->status($test->getCurrentSpiderScan()));
        $test->updateSpiders($test->getCurrentSpiderScan(), $percent);


        $this->manager->persist($test);
        $this->manager->flush();
    }


    public function runAscan(Test $test)
    {
        $id = $this->zap->ascan->scan($test->getSite(), 'True', '', '', '', '', $this->key);
        $test->addAscan($id);

        $this->manager->persist($test);
        $this->manager->flush();
    }

    public function updateAscanStatus(Test $test)
    {
        $percent = intval($this->zap->spider->status($test->getCurrentAscan()));

        $test->updateAscans($test->getCurrentAscan(), $percent);

        $this->manager->persist($test);
        $this->manager->flush();
    }

    public function reloadDatas(Test $test)
    {
        $test->setAlerts($this->zap->core->alerts());
        $test->setMessages($this->zap->core->messages());
        $test->setUrls($this->zap->core->urls());
        $test->setContexts(
          explode(
            ',',
            substr($this->zap->context->contextList(), 1, strlen($this->zap->context->contextList()) - 2)
          )
        );

        $test->setSites($this->zap->core->sites());

        $this->manager->persist($test);
        $this->manager->flush();
    }

    private function scan($target, $session)
    {
//        $this->zap->core->newSession($session, 'True', $this->key);
//        $this->zap->core->setproxy($this->zap->proxy, $this->key);
//        $this->zap->core->Session($session, $this->key);
//        $this->spider($target);
        $this->ascan($target);
//        echo $this->zap->core->saveSession($session, '', $this->key);


    }

    private function loadSession(Test $test)
    {
        $this->zap->core->loadSession($test->getSession(), $this->key);
    }

    private function alerts()
    {

        // Report the results
        $alerts = $this->zap->core->alerts();
        $messages = $this->zap->core->messages();
        $urls = $this->zap->core->urls();
        dump($alerts);

//        foreach ($alerts as $alert) {
//            dump($this->zap->core->alert($alert['messageId']));
//        }

//        echo "Alerts (" . count($alerts) . "):\n";
//        print_r($alerts);

    }

    private function spider($target)
    {
//         Response JSON looks like {"scan":"1"}
        $id = $this->zap->spider->scan($target, '3', $this->key);
        $count = 0;
//
        while (true) {
            if ($count > 40) {
                exit();
            }

            // Response JSON looks like {"status":"50"}

            $progress = intval($this->zap->spider->status($id));

            if ($progress >= 100) {
                break;
            }
            sleep(2);
            $count++;
        }

        // Give the passive scanner a chance to finish
        sleep(5);
    }

    private function ascan($target)
    {
        // Response JSON for error looks like {"code":"url_not_found", "message":"URL is not found"}
        $scan_id = $this->zap->ascan->scan($target, 'True', '', '', '', '', $this->key);

//        $count = 0;
//        while (true) {
//            if ($count > 40) {
//                exit();
//            }
//            $progress = intval($this->zap->ascan->status($scan_id));
//            if ($progress >= 100) {
//                break;
//            }
//            sleep(2);
//            $count++;
//        }
    }

    private function ascanAsUser($target)
    {
        // Response JSON for error looks like {"code":"url_not_found", "message":"URL is not found"}
        $scan_id = $this->zap->ascan->scan($target, '', '', '', '', '', $this->key);
//        $scan_id = $this->zap->ascan->scanAsUser($target, 'context1', 'pcabreus', '', '', '', '', '', $this->key);

        $count = 0;
        while (true) {
            if ($count > 40) {
                exit();
            }
            $progress = intval($this->zap->ascan->status($scan_id));
            if ($progress >= 100) {
                break;
            }
            sleep(2);
            $count++;
        }
    }
} 