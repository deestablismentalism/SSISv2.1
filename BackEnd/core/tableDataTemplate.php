<?php
    declare(strict_types=1);
    require_once __DIR__ . '/./safeHTML.php'; 

    class TableCreator {
        //direct echo functions
        public function generateHorizontalTitles(string $rowName, array $titles) {
            echo '<thead>';
            echo '<tr class="'.$rowName.'">';
            foreach($titles as $rows) {
                echo '<th> ' .htmlspecialchars($rows) . '</th>';
            }
            echo '</tr>';
            echo '</thead>';
        }
        public function generateHorizontalRows(string $className, array $values) {
            echo '<tr class="'.$className.'">';
                foreach($values as $rows) { 
                    if(is_numeric($rows)) {
                        $cleanedRow = $rows;
                    }
                    elseif($rows instanceof safeHTML) {
                        $cleanedRow = $rows;
                    } 
                    else {
                        $cleanedRow = htmlspecialchars($rows);
                    }
                    echo '<td>' . $cleanedRow . '</td>';
                }
            echo '</tr>';
        }
        public function generateVerticalTables(array $keys, array $values) {
            $assoc = array_combine($keys, $values); 
            foreach($assoc as $key => $value) {
                echo '<tr>
                        <td>' . htmlspecialchars($keys) .'</td>
                        <td>' . htmlspecialchars($value) . '</td>
                    </tr>';
            }
        }
        //Used for concatenating directly
        public function returnHorizontalTitles(string $rowName, array $titles) {
            $html = '';
            $html .= '<thead>';
            $html .= '<tr class="'.$rowName.'">';
            foreach($titles as $rows) {
                $html .= '<th> ' .htmlspecialchars($rows) . '</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';

            return $html;
        }
        public function returnHorizontalRows(string $className, array $values) {
            $html = '';
            $html .= '<tr class="'.$className.'">';
            foreach($values as $rows) {
                if(is_numeric($rows) || $rows instanceof safeHTML) {
                    $cleanedRow = $rows;
                }
                else {
                    $cleanedRow = htmlspecialchars($rows);
                }
                $html .= '<td>' .$cleanedRow. '</td>';
            }
            $html .= '</tr>';

            return $html;
        }
    }