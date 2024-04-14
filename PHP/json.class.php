<?php
class JSON {
    private string $link = "";

    /**
     * Constructor for the class.
     *
     * @param string $fileLink The link to the JSON file.
     */
    public function __construct(string $fileLink) {
        $this->link = $fileLink;
    }

    /**
     * Retrieves the JSON file link.
     *
     * @return string The JSON file link.
     */
    public function getLink() : string { return $this->link; }

    /**
     * A description of the entire PHP function.
     *
     * @return Array
     */
    public function read() : array {
        return json_decode(file_get_contents($this->link), true);
    }

    /**
     * A description of the entire PHP function.
     *
     * @param array $jsonArray
     * @return bool
     */
    public function write(array $jsonArray) : bool {
        return (bool) file_put_contents($this->link, json_encode($jsonArray));
    }
}
