Feature: It can view championships

  Scenario: It views championships when none are stored
    Given there are no championships stored
    When a client views all championships
    Then a response with no championships is returned

  Scenario: It views championships when a few are stored
    Given the championship "formulaOne" exists
    Given the championship "motoGp" exists
    Given the championship "wec" exists
    When a client views all championships
    Then a response will all "3" championships is returned
