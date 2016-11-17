<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Test
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="session", type="string", length=255, nullable=TRUE)
     */
    private $session;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime", nullable=TRUE)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=TRUE)
     */
    private $status;
    /**
     * @var string
     *
     * @ORM\Column(name="spiders", type="array")
     */
    private $spiders;

    /**
     * @var string
     *
     * @ORM\Column(name="current_spider_scan", type="string", length=10, nullable=TRUE)
     */
    private $currentSpiderScan;

    /**
     * @var string
     *
     * @ORM\Column(name="ascans", type="array")
     */
    private $ascans;

    /**
     * @var string
     *
     * @ORM\Column(name="current_ascan", type="string", length=10, nullable=TRUE)
     */
    private $currentAscan;

    /**
     * @var array
     *
     * @ORM\Column(name="alerts", type="array")
     */
    private $alerts;

    /**
     * @var array
     *
     * @ORM\Column(name="messages", type="array")
     */
    private $messages;

    /**
     * @var array
     *
     * @ORM\Column(name="urls", type="array")
     */
    private $urls;

    /**
     * @var array
     *
     * @ORM\Column(name="contexts", type="array")
     */
    private $contexts;

    /**
     * @var array
     *
     * @ORM\Column(name="sites", type="array")
     */
    private $sites;


    public function __construct()
    {
        $this->spiders = array();
        $this->ascans = array();
    }

    public function addSpiderScan($scanId, $status = 0)
    {
        $this->spiders[$scanId] = $status;
        $this->currentSpiderScan = $scanId;
        $this->status = 'running';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set site
     *
     * @param string $site
     * @return Test
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set session
     *
     * @param string $session
     * @return Test
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Test
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function addAscan($scanId, $status = 0)
    {
        $this->ascans[$scanId] = $status;
        $this->currentAscan = $scanId;
        $this->status = 'running';
    }

    public function getLastSpider()
    {
        return $this->spiders[$this->currentSpiderScan];
    }

    public function updateSpiders($spider, $percent)
    {
        $this->spiders[$spider] = $percent;
    }

    /**
     * @return string
     */
    public function getSpiders()
    {
        return $this->spiders;
    }

    /**
     * @param string $spiders
     */
    public function setSpiders($spiders)
    {
        $this->spiders = $spiders;
    }


    /**
     * @return string
     */
    public function getCurrentSpiderScan()
    {
        return $this->currentSpiderScan;
    }

    /**
     * @param string $currentSpiderScan
     */
    public function setCurrentSpiderScan($currentSpiderScan)
    {
        $this->currentSpiderScan = $currentSpiderScan;
    }

    public function getLastAscan()
    {
        return $this->ascans[$this->currentAscan];
    }

    public function updateAscans($ascan, $percent)
    {
        $this->ascans[$ascan] = $percent;
    }

    /**
     * @return string
     */
    public function getAscans()
    {
        return $this->ascans;
    }

    /**
     * @param string $ascans
     */
    public function setAscans($ascans)
    {
        $this->ascans = $ascans;
    }

    /**
     * @return string
     */
    public function getCurrentAscan()
    {
        return $this->currentAscan;
    }

    /**
     * @param string $currentAscan
     */
    public function setCurrentAscan($currentAscan)
    {
        $this->currentAscan = $currentAscan;
    }

    /**
     * @return array
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * @param array $alerts
     */
    public function setAlerts($alerts)
    {
        $this->alerts = $alerts;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param array $urls
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

    /**
     * @return array
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @param array $contexts
     */
    public function setContexts($contexts)
    {
        $this->contexts = $contexts;
    }

    /**
     * @return array
     */
    public function getSites()
    {
        return $this->sites;
    }

    /**
     * @param array $sites
     */
    public function setSites($sites)
    {
        $this->sites = $sites;
    }


}
