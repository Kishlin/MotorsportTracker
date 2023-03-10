Feature: It can create Team Presentations

  Scenario: It saves a new team presentation
    Given the "Red Bull Racing" team exists
    When a client creates a team presentation for "Red Bull Racing" with name "Red Bull Racing" and image "redbullracing.webp"
    Then the team presentation is saved
    Then the latest team presentation for "Red Bull Racing" has name "Red Bull Racing" and image "redbullracing.webp"

  Scenario: It can overwrite team presentations
    Given the "Red Bull Racing" team exists
    And the team presentation "Red Bull Racing" exists
    When a client creates a team presentation for "Red Bull Racing" with name "Ford Red Bull Racing" and image "fordredbullracing.webp"
    Then the team presentation is saved
    Then the latest team presentation for "Red Bull Racing" has name "Ford Red Bull Racing" and image "fordredbullracing.webp"
