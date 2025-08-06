<?php
    declare(strict_types=1);
    require_once __DIR__ . '/dbconnection.php'; 

    class TableCreator {

        public function generateHorizontalTitles( $id, string $rowName, array $titles) {
            foreach($titles as $rows) {
                echo '<tr>
                        <th> ' .htmlspecialchars($rows) . '</th>
                        </tr>';
            }
        }
        public function generateHorizontalRows(array $values) {
            echo '<tr>';
                foreach($values as $rows) {
                    echo '<td>' . htmlspecialchars($rows) . '</td>';
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
    }