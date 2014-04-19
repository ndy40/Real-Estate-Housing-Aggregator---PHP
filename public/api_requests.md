Login Page
==========


loginData [ 
	email:string, 
	password: string
], 



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
	id: string  #e.g Aberdeen,
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
	price: number,
	priceType: ['rent', 'sell'],
	houseType: ['detatched', 'semi', 'terrace', 'maisonette'],
	bedrroms: ['1 Bed', '2 Bed', '3 Bed', '4 Bed']
]

Dashboard First Requests
----------------

locationName [
	id: string  
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
	price: number
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


Calculation Page First Requests
----------------------------

#this will be repeated based how many calculations have been saved

butToLet [
	id: sting #property name/address
	monthlyProfit: number,
	yearlyProfit: number,
	rateOfReturn: number
]

butToSell [
	id: string,
	totalProfitLoss: number,
	rateOnInvestment: number
]


