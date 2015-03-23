<?php

namespace Luperi\PageAnnotatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annotation
 *
 * @ORM\Table(name="annotations")
 * @ORM\Entity
 */
class Annotation
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
     * @ORM\Column(name="start", type="text")
     */
    private $start;

    /**
     * @var integer
     *
     * @ORM\Column(name="start_offset", type="integer")
     */
    private $startOffset;

    /**
     * @var string
     *
     * @ORM\Column(name="end", type="text")
     */
    private $end;

    /**
     * @var integer
     *
     * @ORM\Column(name="end_offset", type="integer")
     */
    private $endOffset;

    /**
     * @var string
     *
     * @ORM\Column(name="quote", type="text")
     */
    private $quote;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;


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
     * Set start
     *
     * @param string $start
     * @return Annotation
     */
    public function setStart($start)
    {
        $this->start = $start;
    
        return $this;
    }

    /**
     * Get start
     *
     * @return string 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set startOffset
     *
     * @param integer $startOffset
     * @return Annotation
     */
    public function setStartOffset($startOffset)
    {
        $this->startOffset = $startOffset;
    
        return $this;
    }

    /**
     * Get startOffset
     *
     * @return integer 
     */
    public function getStartOffset()
    {
        return $this->startOffset;
    }

    /**
     * Set end
     *
     * @param string $end
     * @return Annotation
     */
    public function setEnd($end)
    {
        $this->end = $end;
    
        return $this;
    }

    /**
     * Get end
     *
     * @return string 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set endOffset
     *
     * @param integer $endOffset
     * @return Annotation
     */
    public function setEndOffset($endOffset)
    {
        $this->endOffset = $endOffset;
    
        return $this;
    }

    /**
     * Get endOffset
     *
     * @return integer 
     */
    public function getEndOffset()
    {
        return $this->endOffset;
    }

    /**
     * Set quote
     *
     * @param string $quote
     * @return Annotation
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    
        return $this;
    }

    /**
     * Get quote
     *
     * @return string 
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Annotation
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Annotation
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
}