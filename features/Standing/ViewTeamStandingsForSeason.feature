Feature: It can view team standings for a season

  Scenario: It views the team standings
    Given the "team" standing for "Red Bull Racing After Australian GP 2022" exists
    And the "team" standing for "Mercedes After Australian GP 2022" exists
    And the "team" standing for "Red Bull Racing After Emilia Romagna GP 2022" exists
    And the "team" standing for "Mercedes After Emilia Romagna GP 2022" exists
    When a client views the team standings for season "Formula One 2022"
    Then it views the team standings to be
    | team             | points | eventIndex |
    | Red Bull Racing  | 18      | 2          |
    | Mercedes         | 12     | 2          |
    | Red Bull Racing  | 76     | 3          |
    | Mercedes         | 12     | 3          |

  Scenario: It view no team standings
    Given no team standing exist yet
    When a client views the team standings for season "Formula One 2022"
    Then it receives an empty team standings response
