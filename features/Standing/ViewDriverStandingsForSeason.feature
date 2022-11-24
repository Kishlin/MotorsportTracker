Feature: It can view driver standings for a season

  @api
  Scenario: It views the driver standings
    Given the "driver" standing for "Verstappen After Australian GP 2022" exists
    And the "driver" standing for "Hamilton After Australian GP 2022" exists
    And the "driver" standing for "Verstappen After Emilia Romagna GP 2022" exists
    And the "driver" standing for "Hamilton After Emilia Romagna GP 2022" exists
    When a client views the driver standings for season "Formula One 2022"
    Then it views the driver standings to be
    | driver           | points | eventIndex |
    | Max Verstappen   | 0      | 2          |
    | Lewis Hamilton   | 12     | 2          |
    | Max Verstappen   | 34     | 3          |
    | Lewis Hamilton   | 12     | 3          |

  @api
  Scenario: It view no driver standings
    Given no driver standing exist yet
    And the season "Formula One 2022" exists
    When a client views the driver standings for season "Formula One 2022"
    Then it receives an empty driver standings response
