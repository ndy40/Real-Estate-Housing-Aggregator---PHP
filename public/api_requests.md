
Login Page
==========


loginData [ 
	email:string, 
	password: string
] , 

Response: [  #what do you mean by response?

	data: { 
			first_name: value, 
			email: value 
		}
]


Signup Page
===========


signupData [
	name: string,
	email: string,
	locaton: string,
	password string
]


Dashboard Page
==============


locationName [
	name: string  #e.g Aberdeen,
	options :["sevenDays", "twoWeeks", "oneMonth"]
],

locationData [

	one_bed_house {
		price: number,
		change: number
	},

	two_bed_house {
		price: number,
		change: number
	},

	three_bed_house {
		price: number,
		change: number
	},	

	four_bed_house {
		price: number,
		change: number
	}				

],

locationAveragePrice [
	priceType: ['rent', 'sell'],
	houseType: ['detatched', 'semi', 'terrace', 'maisonette'],
	bedrroms: ['1 Bed', '2 Bed', '3 Bed', '4 Bed']
]


Recent Views / Calculation Page
==============================


butToLet [
	monthlyProfit: number,
	yearlyProfit: number,
	rateOfReturn: number
]

butToSell [
	totalProfitLoss: number,
	rateOnInvestment: number
]


