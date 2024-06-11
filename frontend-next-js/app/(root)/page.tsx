import Image from 'next/image'
import Options from "./options"
import Container from './container'

import backgroundImage1 from '/public/background-1.png'
import backgroundImage2 from '/public/background-2.png'
import backgroundImage3 from '/public/background-3.png'
import backgroundImage4 from '/public/background-4.png'
import backgroundImage5 from '/public/background-5.png'

// const backgroundImageArray = [backgroundImage1, backgroundImage2, backgroundImage3, backgroundImage4, backgroundImage5]
// const randomBackgroundImage = backgroundImageArray[Math.floor(Math.random() * backgroundImageArray.length)];

export default async function Home() {

  const backgroundImageArray = [backgroundImage1, backgroundImage2, backgroundImage3, backgroundImage4, backgroundImage5]
  const randomBackgroundImage = backgroundImageArray[Math.floor(Math.random() * backgroundImageArray.length)];
  
  return (
    <div className="relative h-screen w-screen">
    <Image src={randomBackgroundImage} alt='' fill={true} className="object-cover"/>
    <Container>
      <Image width="256" height="256" src={require('/public/logo-crop.png')} alt=""/>
      <h1>Welkom!</h1>
      <p>Verkrijg inzicht over je voortgang middels eenvoudig te begrijpen visualisaties. Het is zo simpel als inloggen met een GitHub-account, en het kiezen van een repository.</p>
      <Options/>
    </Container>
    </div>
  )
}