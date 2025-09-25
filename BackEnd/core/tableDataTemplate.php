<?php
    declare(strict_types=1);
    require_once __DIR__ . '/dbconnection.php'; 

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
            echo '<tbody>';
            echo '<tr class="'.$className.'">';
                foreach($values as $rows) {
                    
                    echo '<td>' . $rows . '</td>';
                }
            echo '</tr>';
            echo '</tbody>';
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
        //return only functions
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
    }