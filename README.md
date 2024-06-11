# Do-i-code
[do-i-code.com](https://do-i-code.com) is een website waarbij je kunt inloggen met je **_eigen_** GitHub account. 
Vervolgens krijg je een overzicht van de Projecten die je in je GitHub account hebt aangemaakt. Deze projecten worden uitgelezen en de issues uit deze projecten worden verwerkt op basis van het initiële startmoment (de `week-n`) en het tijdstip dat het ticket is afgehandeld

## Frontend
In de frontend wordt een Gantt-chart getoond met het verloop van je opleiding (met percentages van het aantal afgeronde opdrachten per repository)

Link naar [implementatie details](./frontend-next-js/README.md)

## Backend
De project informatie (voortgang, repository) uit de frontend wordt via een API naar een backend applicatie gestuurd waarin de data dusdanig verrijkt/aangepast/bijgesteld zodat het mogelijk wordt de voortgang per trainee te zien.

Link naar [implementatie details](./backend-main//README.md)

## Data Science
De data-scientist verwerkt deze data en stelt een aantal rapporten op, te denken aan:
1. Voortgang per trainee
2. Aggregatie per repository zodat inzichtelijk wordt wat de gemiddelde voortgang is en hoe de voortgang van een individuele trainee zich verhoudt tot deze gemiddelde waarde.
