<?php

$iterator = new DirectoryIterator('.');

foreach ($iterator as $file) {
    if ($file->isFile()) {
        echo $file->getFilename() . ' (' . $file->getSize() . ' bytes) <br>';
    }
}

echo '<hr>';

class NewsPaper implements \SplSubject
{
    private $name;
    private $observers = [];
    private $content;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function attach(\SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(\SplObserver $observer)
    {
        $key = array_search($observer, $this->observers, true);
        if($key){
            unset($this->observers[$key]);
        }
    }

    public function notify()
    {
        foreach($this->observers as $observer){
            $observer->update($this);
        }
    }

    public function getContent()
    {
        return "{$this->content} ( {$this->name} )";
    }

    public function breakOutNews(string $content)
    {
        $this->content = $content;
        $this->notify();
    }
}

class Reader implements \SplObserver
{
    public function __construct(public string $name)
    {

    }

    public function update(\SplSubject $subject)
    {
        echo "{$this->name} is reading Breakout news <b> {$subject->getContent()} </b> <br>";
    }
}

$govind = new Reader('Govind');
$arvind = new Reader('Arvind');
$karishma = new Reader('Karishma');
$ravinder = new Reader('Ravinder');

$timesOfIndia = new NewsPaper('Times Of India');
$timesOfIndia->attach($govind);
$timesOfIndia->attach($arvind);
$timesOfIndia->attach($karishma);
$timesOfIndia->attach($ravinder);

echo 'Observer pattern <br><br>';
$timesOfIndia->breakOutNews('GOI has announced 10th June a National Holiday');

echo '<hr>';

echo '<pre>';
// print_r(spl_classes());
$file = new \SplFileInfo('README.md');
echo $file->getFileName();
echo $file->getExtension();