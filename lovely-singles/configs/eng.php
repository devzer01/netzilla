<?php
	class eng
	{
		static $category = array(
					1 => 'affair',
					2 => 'friendship',
					3 => 'one night stand',
					4 => 'relationship');

		static $targetGroup = array(
					1 => 'men',
					2 => 'women',
					3 => 'couples',
					4 => 'groups');

		static $KM_Name = "Lovely Singles";

		static $KM_Website = "Lovely-Singles.com";
		
		static $wemissu = "We miss you on <a href='http://www.lovely-singles.com'>Lovely-singles.com!</a> below are 4 profiles which may match to your search criteria.";

		static $Name = "Name";

		static $Plz = "Area";

		static $Age = "Age";

		static $Civil_status = "Marital status";

		static $Height = "Height";

		static $Descr = "Description";

		static $Appearance = "Appearance";

		static $City = "City";

		static $Year = "Age";

		static $sql_connect_alert = 'Can\'t connect MYSQL.';

		static $sql_database_alert = 'Can\'t connect database.';

		static $yesno = array(
					1 => 'Yes',
					0 => 'No');

		static $picyesno = array(
					1 => 'Yes',
					0 => 'No');

		static $nocomment = array(
						0 => 'No Comment');

		static $month = array(
					1  => 'January',
                    2  => 'February',
                    3  => 'March',
                    4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December');

		/*static $country = array(
					1 => 'Germany',
					2 => 'Austria',
					3 => 'Switzerland',
					4 => 'Europe',
					5 => 'International');*/

		/*static $country = '
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
';*/

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
					1 => 'Male',
					2 => 'Female');

		static $height = array(
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
					37 => "7' 0'' (213cm)");

		static $weight = array(
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
					46 => "300+ lbs. (136kg+)");

		static $appearance = array(
						1 => 'Thin',
						2 => 'Average',
						3 => 'Sporty',
						4 => 'Chubby',
						5 => 'Fat');

		static $eyes_color = array(
						1 => 'Brown',
						2 => 'Blue',
						3 => 'Green',
						4 => 'Other');

		static $hair_color = array(
						1 => 'Black',
						2 => 'Brown',
						3 => 'Blonde',
						4 => 'Red',
						5 => 'Other');

		static $hair_length = array(
						1 => 'Hairless',
						2 => 'Short',
						3 => 'Medium',
						4 => 'Long');

		static $beard = array(
					1 => 'No Beard',
                    2 => 'Some',
                    3 => 'Full Beard',
                    4 => 'Mustache Only');

		static $zodiac = array(
					1 => 'Aquarius',
					2 => 'Pisces',
					3 => 'Aries',
					4 => 'Taurus',
					5 => 'Gemini',
					6 => 'Cancer',
					7 => 'Leo',
					8 => 'Virgo',
					9 => 'Libra',
					10 => 'Scorpio',
					11 => 'Sagittarius',
					12 => 'Capricorn');

		static $sexuality = array(
						1 => 'Homosexual',
						2 => 'Heterosexual',
						3 => 'Bisexual');

		static $status = array(
					1 => 'Single',
                    2 => 'Separated',
                    3 => 'Divorced',
                    4 => 'Widowed',
                    5 => 'Married',
                    6 => 'In a Relationship');

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
		
		static $msg_ban_alert = 'Sms has band message.';

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

		static $email_testmember_subject = 'Lovely Singles: Your username and password';

		static $email_missing ='We miss you on Lovely-singles.com!';

		static $email_reminder_subject = 'There is a new message for you on Lovely-singles.com!';

		static $validation = 'Validation Code';

		static $register_membership_complete = 'Complete';

		static $register_testmembership_complete = 'A registration mail was sent to your e-mail address. Please confirm your account by clicking the link provided in this e-mail!';
		
		static $register_testmembership_complete1 = 'A registration mail was sent to your e-mail address.';
		
		static $register_testmembership_complete2 = 'Please confirm your account by clicking the link provided in this e-mail!<br/><br/>Please check your SPAM/Junk folder, if you do not see the e-mail. Please add us to your <br/>trusted sender list, <br/>so that you will see our updates every time in the future!';

		static $register_testmembership_complete3 = 'Please confirm your account by clicking the link provided in this e-mail!';

		
		static $phone_number_guide_subject = "How to send text messages";
		static $phone_number_guide = "Please be adviced, if you want to send text messages to your favourite contacts, the following steps have to be done:<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Your mobile phone number has to be verified on this website.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. If you want to send text messages to only one profile on this webpage you don`t need to do anything additionally, but if you are writing text messages to different contacts on this website using our text message service, you will have to put the name of your prefered text message recepient at the beginning of your text message (Example: 'nickname: YOUR TEXT').";
		
		
		static $err_blank_valid_code = 'Please fill in verification code.';
		static $err_valid_code = 'Please fill in correct verification code.';
		static $err_valid_code_timeout = 'Your verification time has expired, please resend the verification code.';
		static $register_error = 'The username or e-mail has already been issued.';
		static $err_usrname_format = 'Username format is incorrect.';
		static $err_age_limit = "Due to your age, you're unable to use our service.";
		static $valid_code_resend = 'The system has sent a new verification code to your phone.';
		static $limit_code_resend = 'Sorry, the limit is 3 issues for your verification code.';
		static $err_blank_phone = 'Please fill in your phone number.';
		static $err_require = "Please fill in the required information.";

		static $mobile_valid_success = 'Successfully registered.';

		static $forget_error = 'Incorrect e-mail.';

		static $email_not_in_database = 'This e-mail account is not in our system.';
		static $resend_activation_error = 'This account has been activated already.';

		static $complete = 'This message was sent!';

		static $writemessage_error = 'No Username.';
		static $sms_subject = 'TEXT MESSAGE';

		static $allow = 'This page can only be accessed by members of this site.';

		static $Administrator = 'Administrator';

		static $Membership_Gold = 'Membership-Gold';

		static $Membership_Silver = 'Membership-Silver';

		static $Membership_Bronze = 'Membership-Bronze';

		static $Test_Membership = 'Test-Membership';

		static $and = 'and';

		static $reply_message = 'Hello $receiver,

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

		static $reply_subject2 = "I'd like to meet you";

		static $fotoalbum_alert = 'Please upload .jpg or .jpeg files only.';

		static $activate_alert = 'Username, password or validation code is incorrect.';
		
		static $activate_ready = 'Your registration is complete.';

		static $ecard_subject = 'You received an e-card from one of our members.';

		static $ecard_message = 'You received an e-card from $username';

		static $ecard_send_subject = 'E-card sent successfully';

		static $ecard_send_message = 'E-card sent successfully';

		static $edit_alert = "Can't edit now. Another person is editing this member now.";

		static $view_ecard = 'View Card';

		static $first_time_inbox_subject = "Welcome at Lovely Singles";
		static $first_time_inbox_message = "Hello and welcome to Lovely Singles, your personal account to chat, flirt, relax and make friends.<br/>If you want to talk to someone about your problems or emotional distress, feel free to contact me directly or take a look into my suggestion box.You will find additional information about me such as my diary and my photo album.<br/><br/>I hope you will send me a short message, if you need advice on how to continue.<br/><br/>I hope you have a good time while you are here and success with what you are looking for.<br/><br/>Your 'agony aunt' Gabi ...";

		static $err_valid_bonus_code_timeout = 'Sorry, verification period has expired.';

		static $err_bonus_code_verified = "Sorry, this code has already been verified.";

		static $bonus_SMS_message = "Welcome to Lovely Singles, use this code to verify your BONUS!";

		static $bonus_message_subject = "Get your Bonus code now for FREE!";

		static $bonus_message_content = "<a href=\"[URL]\" class=\"link\">Click here</a> to verify your bonus.";

		static $mobile_verify_message = "Welcome to Lovely Singles, please enter this verification code on the website: ";

		static $progress_bar_profile = "Complete_Profile";

		static $progress_bar_photo = "Photo_Album";

		static $progress_bar_ads = "Lonely_heart_ads";

		static $progress_bar_mobile = "Phone_Number";

		static $progress_bar_mobile_text = "Click to verify <br/>Phone number";

		static $twice_sms_reminder = "If you want to send an SMS to a particular member, please insert their profile name at the beginning of your SMS, e.g. 'Angel23:'";



		static $adminsendemail_intro = "You have received a message from our System Administrator on [KM_Website]!";

		static $adminsendemail_subject = "Get your Bonus code now, for FREE!";

		static $adminsendemail_content = "Welcome to [KM_Website]! There is a bonus code available for you, which you can redeem instantly after you have logged in. Your personal bonus code is as follows: [bonus_code]<br/><br/><a href=\"[URL]\" style=\"color: #FFFFFF; text-decoration: underline;\">Simply click this link do get your free coins!</a>";

		static $adminsendemail_footer1 = "Enjoy your time on [KM_Website]!";

		static $adminsendemail_footer2 = "Note: You are receiving this message because you have signed up with [KM_Website]. ";

		static $adminsendemail_footer3 = "This message was generated automatically, please do not reply to this e-mail.";



		static $sendmessage_email_coin = "Send a message via e-mail [PROFILE_NAME] for only [COIN_COSTS] coins!";
		static $sendmessage_sms_coin = "Send a message via text message or via e-mail [PROFILE_NAME] for only [COIN_COSTS] coins!";



		static $emailAfterEmail_subject = "You have received a message from [PROFILE_NAME] on [KM_Website]!";
		
		static $back_button = "back";
		
		static $next_button = "next";

		static $not_enough_coin = "You don't have enought COIN to send SMS, please refill the COIN in http://www.lovely-singles.com.";




		static $coin_statistics = "Error Message";

		static $admin_manage_contents = "admin_manage_contents";

		static $username_incorrect = 'Username is incorrect.';

		//changepassword.php
		static $chpd1 = "New password saved!";
		static $chpd2 = "These passwords don't match. Try again?";
		static $chpd3 = "Please enter your new password.";
		static $chpd4 = "The current password is incorrect.";
		static $chpd5 = "Please enter your current password.";

		//image_dir.php
		static $img_dir1 = "We don't allow to browse other directory!";

		//mymessage.php
		static $mymsg1 = "Not enough coins to send these messages.";
		static $mymsg2 = "";
		static $mymsg3 = "No standard messages disappeared.";

		//policy-popup.php
		static $plc_popup1 = "Open a popup window";

		//payportal_gp.php
		static $payportal1 = "Account \/ bank account is not supported";
		static $payportal2 = "Bank is not supported";
		static $payportal3 = "The payment request could not be sent. Some information is incorrect!";

		//register.php
		static $register1 = "Your mobile number is already registered";
		static $register2 = "Sorry, there are some errors with the registration, please try again.";

		//search_new.php
		static $search_new1 = "Please specify your criteria";

		//sms_validcode2.php
		static $sms_validcode1 = "The number has been stored and shipped the validation code to you.";
		static $sms_validcode2 = "Validation code is incorrect";
		static $sms_validcode3 = "The validation code has been resent to you! Please wear it now on the left side.";

		//mymessate.php
		static $mobile_ver_required = "You need to verify mobile number before sending SMS";

		//ajaxRequest.php
		static $newmessage = "New";
	}
?>