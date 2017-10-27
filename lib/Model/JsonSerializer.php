<?php

class JsonSerializer implements \JsonSerializable {

    public function jsonSerialize() {
        $out = [];


        foreach ($this->serializable as $property) {

            if (!isset($this->{$property})) {
                continue;
            }

            if (is_object($this->{$property}) && $this->{$property} instanceof JsonSerializable) {
                $out[$property] = $this->{$property}->jsonSerialize();
            } else {
                $out[$property] = $this->{$property};
            }
        }
        return $out;
    }

}
