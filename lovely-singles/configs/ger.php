<?php
	class ger
	{
		static $category = array(
					1 => 'Affäre',
					2 => 'Freundschaft',
					3 => 'One Night Stand',
					4 => 'Beziehung');

		static $targetGroup = array(
					1 => 'Männer',
					2 => 'Frauen',
					3 => 'Paare',
					4 => 'Gruppen');

		static $KM_Name = "Lovely Singles";

		static $KM_Website = "Lovely-Singles.com";
		
    	static $wemissu = "Wir vermissen dich auf <a href='http://www.lovely-singles.com'>lovely-singles.com!</a> Unten findest du die Profile von 4 Mitgliedern, die deinen Vorstellungen hoffentlich ensprechen.";

		static $Name = "Name";

		static $Plz = "PLZ";

		static $Age = "Alter";

		static $Civil_status = "Beziehungsstatus";

		static $Height = "Größe";

		static $Descr = "Beschreibung";

		static $Appearance = "Aussehen";

		static $City = "Stadt";

		static $Year = "Jahre";

		static $sql_connect_alert = 'Can\'t connect MYSQL.';

		static $sql_database_alert = 'Can\'t connect database.';

		static $yesno = array(
					1 => 'Ja',
					0 => 'Nein');

		static $picyesno = array(
					1 => 'Ja',
					0 => 'Egal');

		static $nocomment = array(
						0 => 'Kein Kommentar');

		static $month = array(
					1  => 'Januar',
                    2  => 'Februar',
                    3  => 'März',
                    4  => 'April',
					5  => 'Mai',
					6  => 'Juni',
					7  => 'Juli',
					8  => 'August',
					9  => 'September',
					10 => 'Oktober',
					11 => 'November',
					12 => 'Dezember');

		/*static $country = array(
					1 => 'Germany',
					2 => 'Austria',
					3 => 'Switzerland',
					4 => 'Europe',
					5 => 'International');*/

		static $country = '
<category>
	<country>
		<id>1</id>
		<name>Deutschland</name>
		<state>
			<id>3</id>
			<name>Baden-Württemberg</name>
			<city>
				<id>1</id>
				<name>Esslingen am Neckar</name>
			</city>
			<city>
				<id>2</id>
				<name>Freiburg im Breisgau</name>
			</city>
			<city>
				<id>3</id>
				<name>Heilbronn</name>
			</city>
			<city>
				<id>4</id>
				<name>Heidelberg</name>
			</city>
			<city>
				<id>5</id>
				<name>Karlsruhe</name>
			</city>
			<city>
				<id>6</id>
				<name>Konstanz</name>
			</city>
			<city>
				<id>7</id>
				<name>Ludwigsburg</name>
			</city>
			<city>
				<id>8</id>
				<name>Mannheim</name>
			</city>
			<city>
				<id>9</id>
				<name>Pforzheim</name>
			</city>
			<city>
				<id>10</id>
				<name>Reutlingen</name>
			</city>
			<city>
				<id>11</id>
				<name>Stuttgart</name>
			</city>
			<city>
				<id>12</id>
				<name>Tübingen</name>
			</city>
			<city>
				<id>13</id>
				<name>Ulm</name>
			</city>
			<city>
				<id>14</id>
				<name>Villingen-Schwenningen</name>
			</city>
		</state>
		<state>
			<id>4</id>
			<name>Bayern</name>
			<city>
				<id>15</id>
				<name>Augsburg</name>
			</city>
			<city>
				<id>16</id>
				<name>Erlangen</name>
			</city>
			<city>
				<id>17</id>
				<name>Fürth</name>
			</city>
			<city>
				<id>18</id>
				<name>Ingolstadt</name>
			</city>
			<city>
				<id>19</id>
				<name>München</name>
			</city>
			<city>
				<id>20</id>
				<name>Nürnberg</name>
			</city>
			<city>
				<id>21</id>
				<name>Regensburg</name>
			</city>
			<city>
				<id>22</id>
				<name>Würzburg</name>
			</city>
		</state>
		<state>
			<id>5</id>
			<name>Berlin</name>
			<city>
				<id>23</id>
				<name>Berlin</name>
			</city>
		</state>
		<state>
			<id>6</id>
			<name>Brandenburg</name>
			<city>
				<id>24</id>
				<name>Cottbus</name>
			</city>
			<city>
				<id>25</id>
				<name>Potsdam</name>
			</city>
		</state>
		<state>
			<id>7</id>
			<name>Bremen</name>
			<city>
				<id>26</id>
				<name>Bremen</name>
			</city>
			<city>
				<id>27</id>
				<name>Bremerhaven</name>
			</city>
		</state>
		<state>
			<id>8</id>
			<name>Hamburg</name>
			<city>
				<id>28</id>
				<name>Hamburg</name>
			</city>
		</state>
		<state>
			<id>9</id>
			<name>Hessen</name>
			<city>
				<id>29</id>
				<name>Darmstadt</name>
			</city>
			<city>
				<id>30</id>
				<name>Frankfurt am Main</name>
			</city>
			<city>
				<id>31</id>
				<name>Hanau</name>
			</city>
			<city>
				<id>32</id>
				<name>Kassel</name>
			</city>
			<city>
				<id>33</id>
				<name>Marburg</name>
			</city>
			<city>
				<id>34</id>
				<name>Offenbach am Main</name>
			</city>
			<city>
				<id>35</id>
				<name>Wiesbaden</name>
			</city>
		</state>
		<state>
			<id>10</id>
			<name>Mecklenburg-Vorpommern</name>
			<city>
				<id>36</id>
				<name>Rostock</name>
			</city>
			<city>
				<id>37</id>
				<name>Schwerin</name>
			</city>
		</state>
		<state>
			<id>11</id>
			<name>Niedersachsen</name>
			<city>
				<id>38</id>
				<name>Braunschweig</name>
			</city>
			<city>
				<id>39</id>
				<name>Delmenhorst</name>
			</city>
			<city>
				<id>40</id>
				<name>Göttingen</name>
			</city>
			<city>
				<id>41</id>
				<name>Hannover</name>
			</city>
			<city>
				<id>42</id>
				<name>Hildesheim</name>
			</city>
			<city>
				<id>43</id>
				<name>Oldenburg</name>
			</city>
			<city>
				<id>44</id>
				<name>Osnabrück</name>
			</city>
			<city>
				<id>45</id>
				<name>Salzgitter</name>
			</city>
			<city>
				<id>46</id>
				<name>Wilhelmshaven</name>
			</city>
			<city>
				<id>47</id>
				<name>Wolfsburg</name>
			</city>
		</state>
		<state>
			<id>12</id>
			<name>Nordrhein-Westfalen</name>
			<city>
				<id>48</id>
				<name>Aachen</name>
			</city>
			<city>
				<id>49</id>
				<name>Bergisch Gladbach</name>
			</city>
			<city>
				<id>50</id>
				<name>Bielefeld</name>
			</city>
			<city>
				<id>51</id>
				<name>Bochum</name>
			</city>
			<city>
				<id>52</id>
				<name>Bonn</name>
			</city>
			<city>
				<id>53</id>
				<name>Bottrop</name>
			</city>
			<city>
				<id>54</id>
				<name>Castrop-Rauxel</name>
			</city>
			<city>
				<id>55</id>
				<name>Dorsten</name>
			</city>
			<city>
				<id>56</id>
				<name>Dortmund</name>
			</city>
			<city>
				<id>57</id>
				<name>Duisburg</name>
			</city>
			<city>
				<id>58</id>
				<name>Düren</name>
			</city>
			<city>
				<id>59</id>
				<name>Düsseldorf</name>
			</city>
			<city>
				<id>60</id>
				<name>Essen</name>
			</city>
			<city>
				<id>61</id>
				<name>Gelsenkirchen</name>
			</city>
			<city>
				<id>62</id>
				<name>Gladbeck</name>
			</city>
			<city>
				<id>63</id>
				<name>Gütersloh</name>
			</city>
			<city>
				<id>64</id>
				<name>Hagen</name>
			</city>
			<city>
				<id>65</id>
				<name>Hamm</name>
			</city>
			<city>
				<id>66</id>
				<name>Herne</name>
			</city>
			<city>
				<id>67</id>
				<name>Iserlohn</name>
			</city>
			<city>
				<id>68</id>
				<name>Krefeld</name>
			</city>
			<city>
				<id>69</id>
				<name>Köln</name>
			</city>
			<city>
				<id>70</id>
				<name>Leverkusen</name>
			</city>
			<city>
				<id>71</id>
				<name>Lüdenscheid</name>
			</city>
			<city>
				<id>72</id>
				<name>Lünen</name>
			</city>
			<city>
				<id>73</id>
				<name>Marl</name>
			</city>
			<city>
				<id>74</id>
				<name>Minden</name>
			</city>
			<city>
				<id>75</id>
				<name>Moers</name>
			</city>
			<city>
				<id>76</id>
				<name>Mönchengladbach</name>
			</city>
			<city>
				<id>77</id>
				<name>Mülheim an der Ruhr</name>
			</city>
			<city>
				<id>78</id>
				<name>Münster (Westfalen)</name>
			</city>
			<city>
				<id>79</id>
				<name>Neuss</name>
			</city>
			<city>
				<id>80</id>
				<name>Oberhausen</name>
			</city>
			<city>
				<id>81</id>
				<name>Paderborn</name>
			</city>
			<city>
				<id>82</id>
				<name>Ratingen</name>
			</city>
			<city>
				<id>83</id>
				<name>Recklinghausen</name>
			</city>
			<city>
				<id>84</id>
				<name>Remscheid</name>
			</city>
			<city>
				<id>85</id>
				<name>Rheine</name>
			</city>
			<city>
				<id>86</id>
				<name>Siegen</name>
			</city>
			<city>
				<id>87</id>
				<name>Solingen</name>
			</city>
			<city>
				<id>88</id>
				<name>Velbert</name>
			</city>
			<city>
				<id>89</id>
				<name>Viersen</name>
			</city>
			<city>
				<id>90</id>
				<name>Witten</name>
			</city>
			<city>
				<id>91</id>
				<name>Wuppertal</name>
			</city>
		</state>
		<state>
			<id>13</id>
			<name>Rheinland-Pfalz</name>
			<city>
				<id>92</id>
				<name>Kaiserslautern</name>
			</city>
			<city>
				<id>93</id>
				<name>Koblenz</name>
			</city>
			<city>
				<id>94</id>
				<name>Ludwigshafen am Rhein</name>
			</city>
			<city>
				<id>95</id>
				<name>Mainz</name>
			</city>
			<city>
				<id>96</id>
				<name>Trier</name>
			</city>
			<city>
				<id>97</id>
				<name>Worms</name>
			</city>
		</state>
		<state>
			<id>14</id>
			<name>Saarland</name>
			<city>
				<id>98</id>
				<name>Saarbrücken</name>
			</city>
		</state>
		<state>
			<id>16</id>
			<name>Sachsen</name>
			<city>
				<id>99</id>
				<name>Dresden</name>
			</city>
			<city>
				<id>100</id>
				<name>Leipzig</name>
			</city>
			<city>
				<id>101</id>
				<name>Zwickau</name>
			</city>
		</state>
		<state>
			<id>17</id>
			<name>Sachsen-Anhalt</name>
			<city>
				<id>102</id>
				<name>Dessau</name>
			</city>
			<city>
				<id>103</id>
				<name>Halle (Saale)</name>
			</city>
			<city>
				<id>104</id>
				<name>Magdeburg</name>
			</city>
		</state>
		<state>
			<id>18</id>
			<name>Schleswig-Holstein</name>
			<city>
				<id>105</id>
				<name>Flensburg</name>
			</city>
			<city>
				<id>106</id>
				<name>Kiel</name>
			</city>
			<city>
				<id>107</id>
				<name>Lübeck</name>
			</city>
			<city>
				<id>108</id>
				<name>Neumünster</name>
			</city>
		</state>
		<state>
			<id>19</id>
			<name>Thüringen</name>
			<city>
				<id>109</id>
				<name>Erfurt</name>
			</city>
			<city>
				<id>110</id>
				<name>Gera</name>
			</city>
			<city>
				<id>111</id>
				<name>Jena</name>
			</city>
		</state>
	</country>
	<country>
		<id>2</id>
		<name>Österreich</name>
		<state>
			<id>20</id>
			<name>Österreich</name>
			<city>
				<id>112</id>
				<name>Dornbirn</name>
			</city>
			<city>
				<id>113</id>
				<name>Graz</name>
			</city>
			<city>
				<id>114</id>
				<name>Innsbruck</name>
			</city>
			<city>
				<id>115</id>
				<name>Klagenfurt</name>
			</city>
			<city>
				<id>116</id>
				<name>Linz</name>
			</city>
			<city>
				<id>117</id>
				<name>Salzburg</name>
			</city>
			<city>
				<id>118</id>
				<name>Sankt Pölten</name>
			</city>
			<city>
				<id>119</id>
				<name>Villach</name>
			</city>
			<city>
				<id>120</id>
				<name>Wels</name>
			</city>
			<city>
				<id>121</id>
				<name>Wien</name>
			</city>
		</state>
	</country>
	<country>
		<id>22</id>
		<name>Schweiz</name>
		<state>
			<id>21</id>
			<name>Schweiz</name>
			<city>
				<id>122</id>
				<name>Basel</name>
			</city>
			<city>
				<id>123</id>
				<name>Bern</name>
			</city>
			<city>
				<id>124</id>
				<name>Biel/Bienne</name>
			</city>
			<city>
				<id>125</id>
				<name>Genf</name>
			</city>
			<city>
				<id>126</id>
				<name>Lausanne</name>
			</city>
			<city>
				<id>127</id>
				<name>Lugano</name>
			</city>
			<city>
				<id>128</id>
				<name>Luzern</name>
			</city>
			<city>
				<id>129</id>
				<name>St. Gallen</name>
			</city>
			<city>
				<id>130</id>
				<name>Winterthur</name>
			</city>
			<city>
				<id>131</id>
				<name>Zürich</name>
			</city>
		</state>
	</country>
</category>
';

		static $state = array();
		
		static $city = array(
					27  => 'Aachen',
					111 => 'Arnsberg',
					25  => 'Augsburg',
					124 => 'Bamberg',
					116 => 'Bayreuth',
					75  => 'Bergisch Gladbach',
					1   => 'Berlin',
					18  => 'Bielefeld',
					117 => 'Bocholt',
					16  => 'Bochum',
					19  => 'Bonn',
					65  => 'Bottrop',
					115 => 'Brandenburg a. d. H.',
					29  => 'Braunschweig',
					10  => 'Bremen',
					67  => 'Bremerhaven',
					108 => 'Castrop-Rauxel',
					121 => 'Celle',
					28  => 'Chemnitz',
					76  => 'Cottbus',
					55  => 'Darmstadt',
					113 => 'Delmenhorst',
					106 => 'Dessau',
					119 => 'Detmold',
					123 => 'Dinslaken',
					103 => 'Dorsten',
					7   => 'Dortmund',
					15  => 'Dresden',
					13  => 'Duisburg',
					88  => 'Düren',
					9   => 'Düsseldorf',
					37  => 'Erfurt',
					78  => 'Erlangen',
					8   => 'Essen',
					89  => 'Esslingen am Neckar',
					96  => 'Flensburg',
					5   => 'Frankfurt a.M.',
					35  => 'Freiburg i. B.',
					69  => 'Fürth',
					24  => 'Gelsenkirchen',
					77  => 'Gera',
					118 => 'Gießen',
					109 => 'Gladbek',
					58  => 'Göttingen',
					87  => 'Gütersloh',
					39  => 'Hagen',
					31  => 'Halle (Saale)',
					2   => 'Hamburg',
					42  => 'Hamm',
					93  => 'Hanau',
					11  => 'Hannover',
					54  => 'Heidelberg',
					60  => 'Heilbronn',
					44  => 'Herne',
					79  => 'Hildesheim',
					61  => 'Ingolstadt',
					85  => 'Iserlohn',
					80  => 'Jena',
					83  => 'Kaiserslautern',
					21  => 'Karlsruhe',
					40  => 'Kassel',
					32  => 'Kiel',
					73  => 'Koblenz',
					102 => 'Konstanz',
					30  => 'Krefeld',
					4   => 'Köln',
					12  => 'Leipzig',
					49  => 'Leverkusen',
					94  => 'Ludwigsburg',
					48  => 'Ludwigshafen a. R.',
					36  => 'Lübeck',
					105 => 'Lüdenscheid',
					120 => 'Lüneburg',
					92  => 'Lünen',
					33  => 'Magdeburg',
					41  => 'Mainz',
					20  => 'Mannheim',
					104 => 'Marburg',
					91  => 'Marl',
					99  => 'Minden',
					72  => 'Moers',
					26  => 'Mönchengladbach',
					45  => 'Mülheim a. d. R.',
					3   => 'München',
					23  => 'Münster (Westfalen)',
					107 => 'Neumünster',
					51  => 'Neuss',
					122 => 'Norderstedt',
					14  => 'Nürnberg',
					34  => 'Oberhausen',
					64  => 'Offenbach a. M.',
					50  => 'Oldenburg',
					46  => 'Osnabrück',
					53  => 'Paderborn',
					66  => 'Pforzheim',
					52  => 'Potsdam',
					90  => 'Ratingen',
					59  => 'Recklinghausen',
					57  => 'Regensburg',
					68  => 'Remscheid',
					70  => 'Reutlingen',
					110 => 'Rheine',
					38  => 'Rostock',
					43  => 'Saarbrücken',
					71  => 'Salzgitter',
					86  => 'Schwerin',
					74  => 'Siegen',
					47  => 'Solingen',
					6   => 'Stuttgart',
					82  => 'Trier',
					114 => 'Troisdorf',
					98  => 'Tübingen',
					63  => 'Ulm',
					95  => 'Velbert',
					112 => 'Viersen',
					100 => 'Villingen-Schwenningen',
					22  => 'Wiesbaden',
					97  => 'Wilhelmshaven',
					81  => 'Witten',
					62  => 'Wolfsburg',
					101 => 'Worms',
					17  => 'Wuppertal',
					56  => 'Würzburg',
					84  => 'Zwickau',
					150 => '-------AT-------',
					200 => 'Graz',
					201 => 'Insbruck',
					202 => 'Klagenfurth',
					203 => 'Linz',
					204 => 'Salzburg',
					205 => 'Sankt Pölten',
					206 => 'Villach',
					207 => 'Wels',
					208 => 'Wien',
					250 => '-------CH-------',
					300 => 'Basel',
					301 => 'Bern',
					302 => 'Genf',
					303 => 'Lausanne',
					304 => 'Luzern',
					305 => 'St. Gallen',
					306 => 'Winterthur',
					307 => 'Zürich');

		static $gender = array(
					1 => 'Mann',
					2 => 'Frau');

		/*static $height = array(
					0 => "Please select",
					1 => "4' 0'' (122cm)",
					2 => "4' 1'' (125cm)",
					3 => "4' 2'' (127cm)",
					4 => "4' 3'' (130cm)",
					5 => "4' 4'' (132cm)",
					6 => "4' 5'' (135cm)",
					7 => "4' 6'' (137cm)",
					8 => "4' 7'' (140cm)",
					9 => "4' 8'' (142cm)",
					10 => "4' 9'' (145cm)",
					11 => "4' 10'' (147cm)",
					12 => "4' 11'' (150cm)",
					13 => "5' 0'' (153cm)",
					14 => "5' 1'' (155cm)",
					15 => "5' 2'' (158cm)",
					16 => "5' 3'' (160cm)",
					17 => "5' 4'' (163cm)",
					18 => "5' 5'' (165cm)",
					19 => "5' 6'' (168cm)",
					20 => "5' 7'' (170cm)",
					21 => "5' 8'' (173cm)",
					22 => "5' 9'' (175cm)",
					23 => "5' 10'' (178cm)",
					24 => "5' 11'' (180cm)",
					25 => "6' 0'' (183cm)",
					26 => "6' 1'' (185cm)",
					27 => "6' 2'' (188cm)",
					28 => "6' 3'' (191cm)",
					29 => "6' 4'' (193cm)",
					30 => "6' 5'' (196cm)",
					31 => "6' 6'' (198cm)",
					32 => "6' 7'' (201cm)",
					33 => "6' 8'' (203cm)",
					34 => "6' 9'' (206cm)",
					35 => "6' 10'' (208cm)",
					36 => "6' 11'' (211cm)",
					37 => "7' 0'' (213cm)");*/

		static $height = array(
					0 => "Please select",
					1 => "122 cm",
					2 => "125 cm",
					3 => "127 cm",
					4 => "130 cm",
					5 => "132 cm",
					6 => "135 cm",
					7 => "137 cm",
					8 => "140 cm",
					9 => "142 cm",
					10 => "145 cm",
					11 => "147 cm",
					12 => "150 cm",
					13 => "153 cm",
					14 => "155 cm",
					15 => "158 cm",
					16 => "160 cm",
					17 => "163 cm",
					18 => "165 cm",
					19 => "168 cm",
					20 => "170 cm",
					21 => "173 cm",
					22 => "175 cm",
					23 => "178 cm",
					24 => "180 cm",
					25 => "183 cm",
					26 => "185 cm",
					27 => "188 cm",
					28 => "191 cm",
					29 => "193 cm",
					30 => "196 cm",
					31 => "198 cm",
					32 => "201 cm",
					33 => "203 cm",
					34 => "206 cm",
					35 => "208 cm",
					36 => "211 cm",
					37 => "213 cm");


		/*static $weight = array(
					0 => "Please select",
					1 => "80 lbs. (36kg)",
					2 => "85 lbs. (39kg)",
					3 => "90 lbs. (41kg)",
					4 => "95 lbs. (43kg)",
					5 => "100 lbs. (45kg)",
					6 => "105 lbs. (48kg)",
					7 => "110 lbs. (50kg)",
					8 => "115 lbs. (52kg)",
					9 => "120 lbs. (54kg)",
					10 => "125 lbs. (57kg)",
					11 => "130 lbs. (59kg)",
					12 => "135 lbs. (61kg)",
					13 => "140 lbs. (64kg)",
					14 => "145 lbs. (66kg)",
					15 => "150 lbs. (68kg)",
					16 => "155 lbs. (70kg)",
					17 => "160 lbs. (73kg)",
					18 => "165 lbs. (75kg)",
					19 => "170 lbs. (77kg)",
					20 => "175 lbs. (79kg)",
					21 => "180 lbs. (82kg)",
					22 => "185 lbs. (84kg)",
					23 => "190 lbs. (86kg)",
					24 => "195 lbs. (88kg)",
					25 => "200 lbs. (91kg)",
					26 => "205 lbs. (93kg)",
					27 => "210 lbs. (95kg)",
					28 => "215 lbs. (98kg)",
					29 => "220 lbs. (100kg)",
					30 => "225 lbs. (102kg)",
					31 => "230 lbs. (104kg)",
					32 => "235 lbs. (107kg)",
					33 => "240 lbs. (109kg)",
					34 => "245 lbs. (111kg)",
					35 => "250 lbs. (113kg)",
					36 => "255 lbs. (116kg)",
					37 => "260 lbs. (118kg)",
					38 => "265 lbs. (120kg)",
					39 => "270 lbs. (122kg)",
					40 => "275 lbs. (125kg)",
					41 => "280 lbs. (127kg)",
					42 => "285 lbs. (129kg)",
					43 => "290 lbs. (132kg)",
					44 => "295 lbs. (134kg)",
					45 => "300 lbs. (136kg)",
					46 => "300+ lbs. (136kg+)");*/
		
		static $weight = array(
					0 => "Please select",
					1 => "36 kg",
					2 => "39 kg",
					3 => "41 kg",
					4 => "43 kg",
					5 => "45 kg",
					6 => "48 kg",
					7 => "50 kg",
					8 => "52 kg",
					9 => "54 kg",
					10 => "57 kg",
					11 => "59 kg",
					12 => "61 kg",
					13 => "64 kg",
					14 => "66 kg",
					15 => "68 kg",
					16 => "70 kg",
					17 => "73 kg",
					18 => "75 kg",
					19 => "77 kg",
					20 => "79 kg",
					21 => "82 kg",
					22 => "84 kg",
					23 => "86 kg",
					24 => "88 kg",
					25 => "91 kg",
					26 => "93 kg",
					27 => "95 kg",
					28 => "98 kg",
					29 => "100 kg",
					30 => "102 kg",
					31 => "104 kg",
					32 => "107 kg",
					33 => "109 kg",
					34 => "111 kg",
					35 => "113 kg",
					36 => "116 kg",
					37 => "118 kg",
					38 => "120 kg",
					39 => "122 kg",
					40 => "125 kg",
					41 => "127 kg",
					42 => "129 kg",
					43 => "132 kg",
					44 => "134 kg",
					45 => "136 kg",
					46 => "136 kg+");

		static $appearance = array(
						1 => 'schlank',
						2 => 'normal',
						3 => 'sportlich',
						4 => 'mollig',
						5 => 'rubenshaft');

		static $eyes_color = array(
						1 => 'braun',
						2 => 'blau',
						3 => 'grün',
						4 => 'andere');

		static $hair_color = array(
						1 => 'schwarz',
						2 => 'braun',
						3 => 'blond',
						4 => 'rot',
						5 => 'andere');

		static $hair_length = array(
						1 => 'keine',
						2 => 'kurz',
						3 => 'mittel',
						4 => 'lang');

		static $beard = array(
					1 => 'keinen',
                    2 => 'wenig',
                    3 => 'Vollbart',
                    4 => 'Schnurrbart');

		static $zodiac = array(
					1 => 'Wassermann',
					2 => 'Fische',
					3 => 'Widder',
					4 => 'Stier',
					5 => 'Zwillinge',
					6 => 'Krebs',
					7 => 'Löwe',
					8 => 'Jungfrau',
					9 => 'Waage',
					10 => 'Skorpion',
					11 => 'Schütze',
					12 => 'Steinbock');

		static $sexuality = array(
						1 => 'Homo',
						2 => 'Hetero',
						3 => 'Bisexuell');

		static $status = array(
					1 => 'Single',
                    2 => 'Getrennt',
                    3 => 'Geschieden',
                    4 => 'Verwitwet',
                    5 => 'Verheiratet',
                    6 => 'Beziehung');

		static $phoneCode = array(
				'0150'	=> '0150',
				'0151'	=> '0151',
				'0152'	=> '0152',
				'01520'	=> '01520',
				'0155'	=> '0155',
				'01550'	=> '01550',
				'0157'	=> '0157',
				'0159'	=> '0159',
				'0160'	=> '0160',
				'0161'	=> '0161',
				'0162'	=> '0162',
				'0163'	=> '0163',
				'0169'	=> '0169',
				'0170'	=> '0170',
				'0171'	=> '0171',
				'0172'	=> '0172',
				'0173'	=> '0173',
				'0174'	=> '0174',
				'0175'	=> '0175',
				'0176'	=> '0176',
				'0177'	=> '0177',
				'0178'	=> '0178',
				'0179'	=> '0179');
		
		static $msg_ban_alert = 'Sms has Ban Message.';

		static $msg_not_free_alert = 'Not Free message Display.';

		static $msg_valid_alert = 'Valid Code.';

		static $try1 = 'You have only  ';

		static $try2 = ' time to insert  Code.';

		static $try3 = 'Message is Band.';

		static $free0 = 'Message 0g';

		static $free1 = 'Message  1ghg';

		static $free2 = 'Message  2hg';

		static $free3 = 'Message  3hg';

		static $free4 = 'Message  4h';

		static $username = 'Username';

		static $password = 'Password';

		static $email_testmember_subject = 'Lovely Singles: Dein Benutzername und Passwort';

		static $email_missing ='Wir vermissen dich auf Lovely-singles.com!';

		static $email_reminder_subject = 'Es liegt eine neue Nachricht für dich bereit bei Lovely-singles.com';

		static $validation = 'Validation Code';

		static $register_membership_complete = 'Herzlich Willkommen! Du hast dich erfolgreich auf www.lovely-singles.com registriert und wirst gleich zu deinem Nutzerprofil weiter geleitet!';

		static $register_testmembership_complete = 'Eine Registrierung Mail wurde an Ihre e-mail adresse gesendet. Bitte bestätigen Sie Ihre Rechnung, indem Sie auf den Link in dieser e-mail zur Verfügung gestellt!';
		
		static $register_testmembership_complete1 = 'Bitte überprüfe auch deinen SPAM- oder Junkmail-Ordner in deinem Email-Client ob unsere Mail dort eingegangen ist und füge den Absender gegebenenfalls als vertrauenswürdigen Absender hinzu, damit wir dich auch in Zukunft immer auf dem Laufenden halten können!';

		static $register_testmembership_complete2 = 'Bitte bestätige deine Daten durch Anklicken des darin enthaltenen Links!';

		static $register_testmembership_complete3 = 'Vielen Dank für deine Registrierung bei Lovely-singles.com! Eine Email ist bereits an die von dir angegebene Emailadresse unterwegs.';

		static $phone_number_guide_subject = "Wie der SMS-Versand funktioniert";
		static $phone_number_guide = "Bitte bedenke, dass du nur Nachrichten per SMS an deinen Kontakt verschicken kannst, wenn du folgende Schritte ausgeführt hast:<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Du musst deine Mobilfunkrufnummer auf dieser Internetseite verifiziert haben.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Wenn du an nur einen Kontakt Nachrichten verschickst, dann kannst du problemlos eingehende SMS einfach nur beantworten, solltest du jedoch mit mehreren Kontakten per SMS schreiben, so setze bitte den Namen des Empfängerkontaktes gefolgt von einem : an den Anfang jeder deiner SMS (Beispiel: 'nickname: DEIN TEXT').";
		
		static $err_blank_valid_code = 'Du erhältst innerhalb weniger Sekunden einen Validierungscode. Bitte trage diesen in dem unten stehenden Feld ein, um deine Handynummer zu verifizieren!';
		static $err_valid_code = 'Der Validierungscode scheint falsch zu sein, bitte überprüfe deine Eingaben und versuche es erneut!';
		static $err_valid_code_timeout = 'Die Zeit für die Validierung deiner Handynummer ist leider abgelaufen, bitte klicke auf den unten stehenden Link um dir einen neuen Validierungscode auf dein Handy schicken zu lassen!';
		static $register_error = 'Dein Benutzername oder deine Email Adresse sind bereits registriert';
		static $err_usrname_format = 'Dein Benutzername enthält ungültige Zeichen! Bitte wähle einen anderen Benutzernamen aus und versuche es erneut!';
		static $err_age_limit = 'Leider bist du zu jung, um unseren Service nutzen zu können!';
		static $valid_code_resend = 'Es wurde ein neuer Verifizierungscode an die angegebene Handynummer versendet, bitte habe nur wenige Sekunden Geduld!';
		static $limit_code_resend = 'Diese Funktion wurde bereits zum dritten Mal genutzt und ist jetzt nicht mehr verfügbar!';
		static $err_blank_phone = 'Please fill your phone number';
		static $err_require = "Bitte vervollständige deine Angaben!";	

		static $mobile_valid_success = 'Herzlichen Glückwunsch! Du hast deine Handynummer erfolgreich verifiziert!';

		static $forget_error = 'Bitte trage die Email-Adresse ein!';

		static $email_not_in_database = 'Diese E-Mail-Konto ist nicht in unserem System.';
		static $resend_activation_error = 'Dieses Konto wurde bereits aktiviert.';

		static $complete = 'Diese Nachricht wurde versendet!';

		static $writemessage_error = 'Bitte trage deinen Mitgliedsnamen ein!';
		static $sms_subject = 'TEXT MESSAGE';

		static $allow = 'Diese Funktion ist nur f&uuml;r ';

		static $Administrator = 'Administrator';

		static $Membership_Gold = 'VIP-Mitglieder';

		static $Membership_Silver = 'Premium Mitglieder';

		static $Membership_Bronze = 'Standard Mitglieder';

		static $Test_Membership = 'Test-Mitgliedschaft';

		static $and = 'und';

		static $reply_message = 'Hallo $receiver,

danke für Deine Nachricht. Ich werde mich so schnell wie möglich bei Dir melden.

Liebe Grüße
$sender';

		static $reply_message1 = 'Hallo $receiver,

danke für Deine Nachricht. Ich werde mich so schnell wie möglich bei Dir melden.

Liebe Grüße
$sender';
		static $reply_message2 = 'Hallo $receiver,

Ich bin leider noch nicht freigeschaltet, warte bitte auf mich.

Liebe Grüße
$sender';
		static $reply_message3 = 'Hallo $receiver,

Ich habe auch Interesse an dir! Habe bitte etwas Geduld, bis mein account freigeschaltet ist.

Liebe Grüße
$sender';

		static $standard_message1 = 'Hallo, ich möchte gerne mit dir in Kontakt treten, hast du Lust?

Liebe Grüße
$sender';

		static $standard_message2 = 'Hallo, habe dein Profil gelesen und würde gerne mit dir in Kontakt treten. Bitte melde dich.

Liebe Grüße
$sender';

		static $standard_message3 = 'Hallo, ich habe deine Kontaktanzeige gelesen und  würde gerne mit dir in Kontakt treten. Bitte melde dich.

Liebe Grüße
$sender';

		static $reply_subject = 'Antwort auf deine Nachricht';

		static $reply_subject2 = 'Ich möchte dich kennenlernen';

		static $fotoalbum_alert = 'Bitte nur Bilder im Format .jpg or .jpeg hochladen!';

		static $activate_alert = 'Username, Passwort oder Validierungscode ist falsch.';
		
		static $activate_ready = 'Ihre Registrierung wurde bereits zu einem früherem Zeitpunkt abgeschlossen.';

		static $ecard_subject = 'Eine Lovely-singles.com E-Card für dich!';

		static $ecard_message = 'Hallo! <br> <br> Du hast eine E-Card von $username erhalten. Drücke auf den nachfolgenden Link und erfahre was dein Freund dir schreibt:';

		static $ecard_send_subject = 'Deine Lovely-singles.com E-Card wurde erfolgreich versandt';

		static $ecard_send_message = 'Deine Lovely-singles.com E-Card wurde erfolgreich versandt!';

		static $edit_alert = 'Das Profil befindet sich bereits in Bearbeitung!';

		static $view_ecard = 'E-Card ansehen';

		static $first_time_inbox_subject = "Willkommen bei Lovely-singles.com";
		static $first_time_inbox_message = "Hallo und herzlich Willkommen bei Lovely-singles.com, dem Flirtportal zum Kennenlernen, Flirten und Verlieben.<b /> <b />Wenn du Kummer, Sorgen oder Probleme haben solltest, dann schau doch im Kummerkasten vorbei. Du kannst dort in meinem Tagebuch lesen oder mir eine Nachricht schreiben, wenn du mal nicht weiter weißt.<b /> <b />Ich wünsche dir hier viel Spa&szlig; und viel Erfolg bei deiner Suche.<b /> <b />
			
		Liebe Grüße von deiner Gabi, der \"Kummerkastentante\" :)";

		static $err_valid_bonus_code_timeout = 'Leider ist dieser Bonuscode bereits abgelaufen!';

		static $err_bonus_code_verified = "Dieser Bonuscode wurde bereits eingelöst.";

		static $bonus_SMS_message = "Willkommen bei [URL]. Du hast einen Bonuscode per eMail erhalten. Löse diesen gleich ein um Freecoins zu erhalten: [bonus_code]";

		static $bonus_message_subject = "Hol dir deinen persönlichen Bonuscode!";
		
		static $bonus_message_content = "<a href=\"[URL]\" class=\"link\">Klicke einfach auf diesen Link und hol dir noch heute deine Gratiscoins!</a>";

		static $mobile_verify_message = "Willkommen bei Lovely-singles.com, bitte gebe diesen Verifizierungscode ein: ";

		static $progress_bar_profile = "Vollständiges_Profil";

		static $progress_bar_photo = "Foto_Album";

		static $progress_bar_ads = " Kontaktanzeigen";

		static $progress_bar_mobile = "Telefonnummer";

		static $progress_bar_mobile_text = "Verifiziere deine Handynummer";

		static $twice_sms_reminder = "Wenn du an ein bestimmtes Profil SMS senden m๖chtest, dann setze bitte den Profilnamen an den Anfang deiner SMS, z.B. 'Angel23:'";



		static $adminsendemail_intro = "Du hast eine Nachricht vom System Admin auf [KM_Website] erhalten!";
		
		static $adminsendemail_subject = "Chatte jetzt KOSTENFREI mit unserem GRATIS-COINS-GESCHENK!";

		static $adminsendemail_content = "Und schon wieder verteilen wir Gratiscoins mit denen du kostenlos chatten kannst! Hier dein persönlicher Bonuscode [bonus_code]. Logge dich noch heute ein und löse deinen persönlichen Bonuscode ein, um sofort Gratiscoins zu erhalten! Deine Gratiscoins werden dir sofort gutgeschrieben, also nicht lange warten und einloggen, oder diesen Link benutzen:<br/><a href=[URL]><font color=#f8981d>[URL]</font></a>";
		
		static $adminsendemail_footer1 = "Das Team von [KM_Website] wünscht dir viel Spaß!";
		
		static $adminsendemail_footer2 = "Fussnote: Du erhälst diese Nachricht, weil du dich bei [KM_Website] registriert hast. ";
		
		static $adminsendemail_footer3 = "Diese Nachricht wurde automatisch generiert, also beantworte diese bitte nicht.";



		static $sendmessage_email_coin = "Versende jetzt eine Nachricht über E-Mail [PROFILE_NAME] für nur [COIN_COSTS] Coins!";
		static $sendmessage_sms_coin = "Versende jetzt eine Nachricht über SMS oder E-Mail [PROFILE_NAME] für nur [COIN_COSTS] Coins!";



		static $emailAfterEmail_subject = "Du hast eine Nachricht [PROFILE_NAME] auf [KM_Website] erhalten!";
		
		static $back_button = "zurück";
		
		static $next_button = "vor";

		static $not_enough_coin = "Du hast nicht mehr genug COINS um SMS zu versenden, bitte buche dein COIN-Konto auf unter: http://www.lovely-singles.com.";




		static $coin_statistics = "Fehlermeldung";

		static $admin_manage_contents = "admin_manage_contents";

		static $username_incorrect = 'Benutzername ist falsch.';

		//changepassword.php
		static $chpd1 = "Neues Passwort gespeichert!";
		static $chpd2 = "Diese Passwörter stimmen nicht überein. Versuchen Sie es erneut?";
		static $chpd3 = "Bitte geben Sie Ihr neues Passwort.";
		static $chpd4 = "Das aktuelle Passwort ist nicht korrekt.";
		static $chpd5 = "Bitte geben Sie Ihr aktuelles Passwort.";

		//image_dir.php
		static $img_dir1 = "Wir erlauben keine andere Verzeichnis zu durchsuchen!";

		//mymessage.php
		static $mymsg1 = "Nicht genug Münzen, um diese Nachrichten zu senden.";
		static $mymsg2 = "";
		static $mymsg3 = "Keine Standard Nachrichten mehr vorhanden.";

		//policy-popup.php
		static $plc_popup1 = "Öffnen Sie ein Popup-Fenster";

		//payportal_gp.php
		static $payportal1 = "Konto \/ Bankverbindung wird nicht unterstützt";
		static $payportal2 = "Bank wird nicht unterstützt";
		static $payportal3 = "Die Zahlungsanfrage konnte nicht gesendet werden. Einige Angaben sind fehlerhaft!";

		//register.php
		static $register1 = "Dein Mobilfunknummer ist bereits registriert";
		static $register2 = "Leider sind einige Fehler bei der Anmeldung, bitte versuchen Sie es erneut.";

		//search_new.php
		static $search_new1 = "Bitte geben Sie Ihre Kriterien";

		//sms_validcode2.php
		static $sms_validcode1 = "Die Nummer wurde gespeichert und der Validierungscode an dich versandt.";
		static $sms_validcode2 = "Validation Code ist nicht korrekt";
		static $sms_validcode3 = "Der Validierungscode wurde erneut an dich gesendet! Bitte trage ihn nun auf der linken Seite ein.";

		//mymessate.php
		static $mobile_ver_required = "Sie müssen sich Handy-Nummer vor dem Senden von SMS überprüfen";

		//ajaxRequest.php
		static $newmessage = "Neu";
	}
?>